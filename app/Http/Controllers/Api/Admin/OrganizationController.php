<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;
use App\Models\organizationgroups;
use App\Models\Receiver;
use App\Models\Tipper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the org resource.
     */
    public function index()
    {
        /**
         * @return Illuminate\Pagination\LengthAwarePaginator
         */
        $pagination = Organization::paginate(15);

        return response()->json([
            'items' => $pagination->getCollection()->map(function($item) {
                return $item->getAttributesForTable();
            }),
            'currentPage' => $pagination->currentPage(),
            'lastPage' => $pagination->lastPage(),
            'attrnames' => $this->getAttributesAsKeys($pagination->getCollection())
        ]);
    }

    /**
     * Save a new org
     */
    public function store(OrganizationRequest $request)
    {
        // Return the validated form data
        $validated = $request->validated();

        if ( !empty($request['slug']) )
            $organization_slug = Str::slug($request['slug']);
        else
            $organization_slug = Str::slug($request['name']);

        $organization = Organization::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'slug' => $organization_slug,
            'password' => Hash::make($validated['password']),
            'status' => Organization::setStatus($validated['status']),
        ]);

        if (! $organization) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response = ['serverError' => 'Something went wrong on my side. Mr. Server'];
        } else {
            $responseCode = Response::HTTP_OK;
            $response = ['success' => 'Passed data were successfully saved'];
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'slug' => $organization['slug'],
        ];

        Mail::send('emails.organization.register', $data, function($messages) use ($validated) {
            $messages->to( $validated['email'] );
            $messages->subject('Credentials for your organization');
        });

        return response()->json($response, $responseCode);
    }

    public function create_group(Request $request, $org)
    {
        $org = Organization::where('slug', '=', $org)->first();
        $org_id = $org->id;

        $organizationgroups = organizationgroups::create([
            'name' => $request->name,
            'users' => $request->users,
            'org_id' => $org_id
        ]);

        if ( !empty($request->all()['users']['group']['receivers']) ) {
        foreach ($request->all()['users']['group']['receivers'] as $receiver):
            Receiver::where(['id' => $receiver])->update([
                'if_in_group' => $organizationgroups->id
            ]);
        endforeach;
        }

        if ( !empty($request->all()['users']['group']['tippers']) ) {
        foreach ($request->all()['users']['group']['tippers'] as $tipper):
            Tipper::where(['id' => $tipper])->update([
                'if_in_group' => $organizationgroups->id
            ]);
        endforeach;
        }

        if (! $organizationgroups) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response = ['serverError' => 'Something went wrong on my side. Mr. Server'];
        } else {
            $responseCode = Response::HTTP_OK;
            $response = ['success' => 'Passed data were successfully saved'];
        }

        return redirect(route('organizationgroups.index', $org->slug));

        return response()->json($response, $responseCode);
    }

    /**
     * Show the specified org
     */
    public function show($id)
    {
        $org = Organization::find($id);

        return response()->json($org);
    }

    public function orggroup_show($slug, $id)
    {
        $org = Organization::where(['slug' => $slug])->first();
        $group = organizationgroups::where(['id' => $id])->first();
        $receivers = Receiver::where(['if_in_group' => $id])->get();
        $tippers = Tipper::where(['if_in_group' => $id])->get();

        return view('organization.groups.show', compact('org', 'group', 'receivers', 'tippers'));
    }

    /**
     * Update the specified org
     */
    public function update(OrganizationRequest $request)
    {
        // Return the validated form data
        $validated = $request->validated();

        $response = [];

        $updateableFields = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => Organization::setStatus($validated['status']),
        ];

        if ( !empty($request['slug']) )
            $updateableFields['slug'] = Str::slug($request['slug']);
        else
            $updateableFields['slug'] = Str::slug($request['name']);

        if (!empty($validated['password'])) {
            $updateableFields['password'] = Hash::make($validated['password']);
        }

        $org = Organization::where(['id' => $validated['id']])->update($updateableFields);

        if (! $org) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response = ['serverError' => 'Something went wrong on my side. Mr. Server'];
        } else {
            $responseCode = Response::HTTP_OK;
            $response = ['success' => 'Passed data were successfully saved'];
        }

        return response()->json($response, $responseCode);
    }

    public function update_group(Request $request, $org_id)
    {
        $org_info = Organization::where('slug', '=', $org_id)->first();
        $org_group = organizationgroups::where(['id' => $request->id])->first();
        if ( !empty($org_group->users['group']['receivers']) )
            $prev_receiver_users = $org_group->users['group']['receivers'];
        if ( !empty($request->users['group']['receivers']) )
            $new_receiver_users = $request->users['group']['receivers'];

        if ( !empty($prev_receiver_users) && !empty($new_receiver_users) ) {
            $removed_receivers = array_diff($prev_receiver_users, $new_receiver_users);
            $new_receivers = array_diff($new_receiver_users, $prev_receiver_users);
            foreach ( $removed_receivers as $receiver ):
                Receiver::where(['id' => $receiver])->update([
                    'if_in_group' => null
                ]);
            endforeach;

            foreach ( $new_receivers as $receiver ):
                Receiver::where(['id' => $receiver])->update([
                    'if_in_group' => $org_group->id
                ]);
            endforeach;
        }

        if ( !empty($prev_receiver_users) && empty($new_receiver_users) ) {
            foreach ( $prev_receiver_users as $receiver ):
                Receiver::where(['id' => $receiver])->update([
                    'if_in_group' => null
                ]);
            endforeach;
        }

        if ( empty($prev_receiver_users) && !empty($new_receiver_users) ) {
            foreach ( $new_receiver_users as $receiver ):
                Receiver::where(['id' => $receiver])->update([
                    'if_in_group' => $org_group->id
                ]);
            endforeach;
        }


        if ( !empty($org_group->users['group']['tippers']) )
            $prev_tipper_users = $org_group->users['group']['tippers'];
        if ( !empty($request->users['group']['tippers']) )
            $new_tipper_users = $request->users['group']['tippers'];

        if ( !empty($prev_tipper_users) && !empty($new_tipper_users) ) {
            $removed_tippers = array_diff($prev_tipper_users, $new_tipper_users);
            $new_tippers = array_diff($new_tipper_users, $prev_tipper_users);
            foreach ( $removed_tippers as $tipper ):
                Tipper::where(['id' => $tipper])->update([
                    'if_in_group' => null
                ]);
            endforeach;

            foreach ( $new_tippers as $tipper ):
                Tipper::where(['id' => $tipper])->update([
                    'if_in_group' => $org_group->id
                ]);
            endforeach;
        }


        $response = [];
        $org = organizationgroups::where(['id' => $request->id])->update(
            [
                'name' => $request->name,
                'users' => $request->users
            ]
        );

        if (! $org) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response = ['serverError' => 'Something went wrong on my side. Mr. Server'];
        } else {
            $responseCode = Response::HTTP_OK;
            $response = ['success' => 'Passed data were successfully saved'];
        }

        return redirect(route('organizationgroups.index', $org_info->slug));

        return response()->json($response, $responseCode);
    }

    /**
     * Remove the specified organization from storage.
     */
    public function destroy($id)
    {
        $deleted = Organization::destroy($id);

        $response = [];

        if ($deleted) {

            /**
             * @return Illuminate\Pagination\LengthAwarePaginator
             */
            $pagination = Organization::paginate(15);

            $response = [
                'items' => $pagination->getCollection()->map(function($item) {
                    return $item->getAttributesForTable();
                }),
                'currentPage' => $pagination->currentPage(),
                'lastPage' => $pagination->lastPage(),
                'attrnames' => $this->getAttributesAsKeys($pagination->getCollection()),
                'success' => 'It is successfully deleted',
            ];

        } else {
            $response['error'] = 'None of the items was deleted';
        }

        return response()->json($response);
    }

    public function destroy_group($slug, $id)
    {
        $org = organizationgroups::where(['id' => $id])->first();
        if ( !empty($org->users['group']['receivers']) ) {
            $receivers = $org->users['group']['receivers'];

            foreach ( $receivers as $receiver )
                Receiver::where(['id' => $receiver])->update(['if_in_group' => null]);
        }

        if ( !empty($org->users['group']['tippers']) ) {
            $tippers = $org->users['group']['tippers'];

            foreach ( $tippers as $tipper )
                Tipper::where(['id' => $tipper])->update(['if_in_group' => null]);
        }

        $deleted = organizationgroups::destroy($id);

        $response = [];

        return redirect(route('organizationgroups.index', $slug));

        if ($deleted) {

            /**
             * @return Illuminate\Pagination\LengthAwarePaginator
             */
            $pagination = organizationgroups::paginate(15);

            $response = [
                'items' => $pagination->getCollection()->map(function($item) {
                    return $item->getAttributesForTable();
                }),
                'currentPage' => $pagination->currentPage(),
                'lastPage' => $pagination->lastPage(),
                'attrnames' => $this->getAttributesAsKeys($pagination->getCollection()),
                'success' => 'It is successfully deleted',
            ];

        } else {
            $response['error'] = 'None of the items was deleted';
        }

        return response()->json($response);
    }

    /**
     * Find a searchable getting from an organization
     */
    public function search(Request $request)
    {
        $searchable = $request->input('search');
        $org = Organization::where('name', 'like', "%$searchable%")
            ->limit(15)
            ->get();
        return response()->json($org);
    }

    /**
     * Select a first item from the collection and retrieve all attribute names of the model
     */
    private function getAttributesAsKeys(Collection $collection)
    {
        $model = $collection->first();
        return $model ? $model->getAttributeNamesForTable() : null;
    }
}
