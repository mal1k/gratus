<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Tipper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class TipperController extends Controller
{
    public function index($name)
        {
            $org = Organization::where('slug', '=', $name)->first();
            $tippers = Tipper::where('org_id', '=', $org->id)
                            ->paginate(15);

            return view('organization.tippers.dashboard', compact('org', 'tippers'));

            return response()->json([
                'items' => $tippers->getCollection()->map(function($item) {
                    return $item->getAttributesForTable();
                }),
                'currentPage' => $tippers->currentPage(),
                'lastPage' => $tippers->lastPage(),
                'attrnames' => $this->getAttributesAsKeys($tippers->getCollection())
            ]);
        }

    public function edit(Tipper $tipper, $slug, $code)
    {
        $tipper = Tipper::where('password', '=', $code)
                        ->where('status', '=', 0)
                        ->first();

        if ( empty($tipper) )
            return abort(403, 'code does not exist');

        $org = Organization::where('slug', '=', $slug)->first();
        $org_id = $org->id;
        return view('organization.tippers.form', compact('org', 'code', 'org_id', 'tipper'));
    }

    public function invite_form(Tipper $tipper, $slug)
    {
        $org = Organization::where('slug', '=', $slug)->first();
        return view('organization.tippers.inviteform', compact('org'));
    }

    public function confirm($slug, $code)
    {
        $receiver = Tipper::where('if_in_group', '=', $code)
                        ->first();

        if ( empty($receiver) )
            return abort(403, 'code does not exist');

        $org = Organization::where('slug', '=', $slug)->first();
        $org_id = $org->id;

        Tipper::where('if_in_group', '=', $code)->update([
            'org_id' => $org_id,
            'status' => 200,
            'if_in_group' => null
        ]);

        return view('organizationVerificated');
        // return view('organization.receivers.form', compact('org', 'code', 'org_id', 'receiver'));
    }

    public function store(Request $request, $org)
    {
        $org_info = Organization::where('slug', '=', $org)->first();
        $validated = $request->validate([
            'email' => 'required',
        ]);

        if ( !empty(Tipper::where('email', '=', $request->email)->where('org_id', '=', null)->first()) ) {
            $code = rand(000000, 999999);
            $tipper = Tipper::where(['email' => $request->email])->update([
                'org_id' => $org_info->id,
                'if_in_group' => $code,
                'status' => 0,
            ]);
            $data = [
                'email' => $validated['email'],
                'code' => $code,
                'slug' => $org_info->slug,
            ];
            Mail::send('emails.organization.old-tipper-invite', $data, function($messages) use ($validated) {
                $messages->to( $validated['email'] );
                $messages->subject('Invite to organization');
            });
        } elseif ( empty(Tipper::where('email', '=', $request->email)->first()) ) {
            $code = Str::random(16);
            $data = [
                'email' => $validated['email'],
                'code' => $code,
                'slug' => $org_info->slug,
            ];
            Mail::send('emails.organization.tipper-invite', $data, function($messages) use ($validated) {
                $messages->to( $validated['email'] );
                $messages->subject('Invite to organization');
            });

            // Return the validated form data
            $tipper = Tipper::create([
                'email' => $request->email,
                'org_id' => $org_info->id,
                'first_name' => ' ',
                'last_name' => ' ',
                'password' => $code,
                'status' => 0
            ]);

            Mail::send('emails.organization.tipper-invite', $data, function($messages) use ($validated) {
                $messages->to( $validated['email'] );
                $messages->subject('Invite to organization');
            });

            $responseCode = Response::HTTP_OK;
            $response = ['success' => 'Passed data were successfully saved'];
        } else {
            return Redirect::back()->withErrors(['error' => 'You cannot invite someone who is already in the organization']);
        }

        return redirect(route('organizationtippers.index', $org_info->slug));
    }

    public function show($slug, $id)
    {
        $tipper = Tipper::where('id', '=', $id)->first();
        $link = URL::to('/organization/' . $slug . '/tippers');
        $org = Organization::where('slug', '=', $slug)->first();
        return view('organization.tippers.show', compact('tipper', 'org'));
    }

    public function invite_auth(Request $request, $org_id, $code)
    {
        $tipper_info = Tipper::where('password', '=', $code)
        ->where('status', '=', 0)
        ->first();

        if ( empty($tipper_info) )
            return abort(403, 'code does not exist');

        $org_info = Organization::where('slug', '=', $org_id)->first();

        if ( isset($tipper_info) && isset($org_info) )
            Tipper::where(['id' => $tipper_info->id])->update([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'password'   => Hash::make($request->password),
                'status'     => 200,
            ]);


        $responseCode = Response::HTTP_OK;
        $response = ['success' => 'Passed data were successfully saved'];

        return response()->json($response, $responseCode);
    }

    public function update(Request $request, $org_id)
    {
        $org_info = Organization::where('slug', '=', $org_id)->first();

        Tipper::where(['id' => $request->id])->update($request->all());

        $responseCode = Response::HTTP_OK;
        $response = ['success' => 'Passed data were successfully saved'];

        return redirect(route('organizationtippers.index', $org_info->slug));
        return response()->json($response, $responseCode);
    }

    public function remove($slug, $id)
    {
        $receiver = Tipper::where([
            'id' => $id
        ])->update([
            'status' => 0,
            'org_id' => null,
            'if_in_group' => null
            ]);

        return redirect(route('organizationtippers.index', $slug));
    }

    public function destroy($slug, $id)
    {
        $deleted = Tipper::destroy($id);

        $response = [];

        return redirect(route('organizationtippers.index', $slug));

        if ($deleted) {

            /**
             * @return Illuminate\Pagination\LengthAwarePaginator
             */
            $pagination = Tipper::paginate(15);

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

    public function search(Request $request)
    {
        $searchable = $request->input('search');
        $tippers = Tipper::where('first_name', 'like', "%$searchable%")
            ->orWhere('last_name', 'like', "%$searchable%")
            ->orWhere('email', 'like', "%$searchable%")
            ->limit(15)
            ->get();
        return response()->json($tippers);
    }

    private function getAttributesAsKeys(Collection $collection)
    {
        $model = $collection->first();
        return $model ? $model->getAttributeNamesForTable() : null;
    }

    private function getValidatedArray($array) : array
    {
        $validatedString = '';

        foreach ($array as $key => $value) {
            $validatedString .= $key .'='. $value .'&';
        }

        parse_str($validatedString, $validatedArray);

        $validatedArray['receiver_data']['is_kyc_passed'] = (bool) $validatedArray['receiver_data']['is_kyc_passed'];

        return $validatedArray;
    }
}
