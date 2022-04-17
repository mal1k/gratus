<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\{
    DB,
    Hash,
    Mail,
    Redirect,
    URL,
    Validator
};
use App\Http\Requests\ReceiverRequest;
use App\Models\{
    BankAccount,
    CreditCard,
    Organization,
    organizationgroups,
    Receiver,
    ReceiverData,
    Transactions
};
use Carbon\Carbon;

class ReceiverController extends Controller
{
    /**
     * Display a listing of the service provider resource.
     */
    public function index()
    {
        /**
         * @return Illuminate\Pagination\LengthAwarePaginator
         */
        $pagination = Receiver::paginate(15);

        return response()->json([
            'items' => $pagination->getCollection()->map(function($item) {
                $org_name = null;
                $group_name = null;

                if ( $item->if_in_group ) {
                    $org = Organization::where(['id' => $item->org_id])->first();
                    if ( $org )
                        $org_name = $org->name;
                }
                else
                    $org_name = null;

                if ( $item->if_in_group ) {
                    $group = organizationgroups::where(['id' => $item->if_in_group])->first();
                    if ( $group )
                        $group_name = $group->name;
                }
                else
                    $group_name = null;

                return [
                    'id'           => $item->id,
                    'first_name'   => $item->first_name,
                    'last_name'    => $item->last_name,
                    'email'        => $item->email,
                    'nfc'          => $item->nfc_chip,
                    'organization' => $org_name,
                    'group'        => $group_name,
                    'status'       => $item->status
                ];
            }),
            'currentPage' => $pagination->currentPage(),
            'lastPage' => $pagination->lastPage(),
            'attrnames' => $this->getAttributesAsKeys($pagination->getCollection())
        ]);
    }

    /**
     * Save a new receiver
     */
    public function store(ReceiverRequest $request)
    {
        // Return the validated form data
        $validated = $request->validated();

        $validatedArray = $this->getValidatedArray($validated);

        try {

            DB::beginTransaction();

            $receiverFields = $validatedArray['receiver'];

            $receiverFields['status'] = Receiver::setStatus($receiverFields['status']);

            if (!empty($receiverFields['password'])) {
                $receiverFields['password'] = Hash::make($receiverFields['password']);
            }

            $receiver = Receiver::create($receiverFields);

            $receiverDataFields = $validatedArray['receiver_data'];
            $receiverDataFields['birth_date'] = Carbon::createFromFormat('m-d-Y', $receiverDataFields['birth_date'])->format('Y-m-d');

            $receiverDataFields['receiver_id'] = $receiver->id;
            ReceiverData::create($receiverDataFields);

            $creditCardFields = $validatedArray['credit_card'];
            $creditCardFields['cardable_id'] = $receiver->id;
            $creditCardFields['cardable_type'] = Receiver::class;

            if (empty($creditCardFields['secret_code'])) {
                $creditCardFields['secret_code'] = null;
            }

            CreditCard::create($creditCardFields);

            $bankAccountFields = $validatedArray['bank_account'];
            $bankAccountFields['accountable_id'] = $receiver->id;
            $bankAccountFields['accountable_type'] = Receiver::class;
            BankAccount::create($bankAccountFields);

            $responseCode = Response::HTTP_OK;
            $response = ['success' => 'Passed data were successfully saved'];

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            $errorMessage = $e->getMessage();
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response = ['serverError' => "The server encountered with some error and the error message is: $errorMessage."];

        }

        return response()->json($response, $responseCode);
    }

    /**
     * Display the specified receiver for an update and an overview
     */
    public function show($id)
    {
        $receiver = Receiver::with(['receiverData', 'creditCard', 'bankAccount'])->find($id);
        // Organization::
        // Tipper::

        // Organization

        // Tipper: first_name, last_name
        // Organization: name

        // $receiver['transactions'] = Transactions::where(['receiver_id' => $receiver->id])->get();

        $receiver['transactions'] = Transactions::where(['receiver_id' => $receiver->id])
            ->leftJoin("tippers", "tippers.id", "=", "transactions.tipper_id")
            ->leftJoin("organizations", "organizations.id", "=", "transactions.org_id")
            ->get(['transactions.*', 'tippers.first_name', 'tippers.last_name', 'organizations.name as org_name']);

        if ( !empty($receiver->receiverData->birth_date) )
            $receiver->receiverData->birth_date = Carbon::parse($receiver->receiverData->birth_date)->format('m-d-Y');
        return response()->json($receiver);
    }

    /**
     * Update the specified receiver
     */
    public function update(ReceiverRequest $request)
    {
        // Return the validated form data
        $validated = $request->validated();

        $validatedArray = $this->getValidatedArray($validated);
        try {

            DB::beginTransaction();

            $receiverFields = $validatedArray['receiver'];
            $validatedArray['receiver_data']['receiver_id'] = $receiverFields['id'];

            $receiverFields['status'] = Receiver::setStatus($receiverFields['status']);

            if (!empty($receiverFields['password'])) {
                $receiverFields['password'] = Hash::make($receiverFields['password']);
            }

            Receiver::where(['id' => $validatedArray['receiver']['id']])->update($receiverFields);

            $findReceiverData = ReceiverData::where('id', '=', $receiverFields['id'])->get()->all();
            $validatedArray['receiver_data']['birth_date'] = Carbon::createFromFormat('m-d-Y', $validatedArray['receiver_data']['birth_date'])->format('Y-m-d');

            if ( !empty($findReceiverData) )
                ReceiverData::where([
                    'id' => $receiverFields['id']
                ])->update($validatedArray['receiver_data']);
            else
                ReceiverData::create($validatedArray['receiver_data']);

            // CreditCard::where([
            //     'id' => $validatedArray['credit_card']['id']
            // ])->update($validatedArray['credit_card']);

            // BankAccount::where([
            //     'id' => $validatedArray['bank_account']['id']
            // ])->update($validatedArray['bank_account']);

            $responseCode = Response::HTTP_OK;
            $response = ['success' => 'Passed data were successfully saved'];

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            $errorMessage = $e->getMessage();
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response = ['serverError' => "The server encountered with some error and the error message is: $errorMessage."];

        }

        return response()->json($response, $responseCode);
    }
    public function receiver_edit($slug, $id)
    {
        $org = Organization::where(['slug' => $slug])->first();
        $receiver = Receiver::find($id);
        return view('organization.receivers.editForm', compact('org', 'receiver'));
    }

    public function receiver_update(Request $request, $slug, $id)
    {
        $data = request()->all();
        $data = request()->except(['_token']);
        Receiver::where(['id' => $id])->update($data);
        return redirect(route('organizationreceivers.index', $slug));
    }

    /**
     * Remove the specified provider from storage.
     */
    public function destroy($id)
    {
        $receiver = Receiver::with(['receiverData', 'creditCard', 'bankAccount'])
                ->find($id);
        if (!empty($receiver->receiverData)) {
            $receiver->receiverData->delete();
        }
        if (!empty($receiver->creditCard)) {
            $receiver->creditCard->delete();
        }
        if (!empty($receiver->bankAccount)) {
            $receiver->bankAccount->delete();
        }

        $response = [];

        if ($receiver->delete()) {

            /**
             * @return Illuminate\Pagination\LengthAwarePaginator
             */
            $pagination = Receiver::paginate(15);

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
     * Find a searchable getting from an provider
     */
    public function search(Request $request)
    {
        $searchable = $request->input('search');
        $receivers = Receiver::where('first_name', 'like', "%$searchable%")
            ->orWhere('last_name', 'like', "%$searchable%")
            ->orWhere('email', 'like', "%$searchable%")
            ->limit(15)
            ->get();

        foreach ( $receivers as $receiver ):
            $receivers_list['id'] = $receiver->id;
            $receivers_list['first_name'] = $receiver->first_name;
            $receivers_list['last_name'] = $receiver->last_name;
            $receivers_list['email'] = $receiver->email;
            $receivers_list['status'] = $receiver->status;
        endforeach;

        return response()->json($receivers_list);
    }

    /**
     * Select a first item from the collection and retrieve all attribute names of the model
     */
    private function getAttributesAsKeys(Collection $collection)
    {
        $model = $collection->first();
        return $model ? $model->getAttributeNamesForTable() : null;
    }

    /**
     * Extract the true array from the request validated array
     */
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

    public function org_index($name)
    {
        $org = Organization::where('slug', '=', $name)->first();
        $receivers = Receiver::where('org_id', '=', $org->id)
                        ->paginate(15);

        return view('organization.receivers.dashboard', compact('org', 'receivers'));

        return response()->json([
            'items' => $receivers->getCollection()->map(function($item) {
                return $item->getAttributesForTable();
            }),
            'currentPage' => $receivers->currentPage(),
            'lastPage' => $receivers->lastPage(),
            'attrnames' => $this->getAttributesAsKeys($receivers->getCollection())
        ]);
    }

    public function group_schedule($name, $id)
    {
        $org = Organization::where('slug', '=', $name)->first();
        $group = organizationgroups::where('id', '=', $id)->first();
        $receivers = null;
        if ( isset($group->users['group']['receivers']) ) {
            $receivers_id = $group->users['group']['receivers'];
            foreach ( $receivers_id as $receiver )
                $receivers[] = Receiver::where(['id' => $receiver])->first();
        }

        return view('organization.schedule.dashboard', compact('org', 'group', 'receivers'));

        return response()->json([
            'items' => $receivers->getCollection()->map(function($item) {
                return $item->getAttributesForTable();
            }),
            'currentPage' => $receivers->currentPage(),
            'lastPage' => $receivers->lastPage(),
            'attrnames' => $this->getAttributesAsKeys($receivers->getCollection())
        ]);
    }

    public function org_edit(Receiver $receiver, $slug, $code)
    {
        $receiver = Receiver::where('password', '=', $code)
                        ->where('status', '=', 0)
                        ->first();

        if ( empty($receiver) )
            return abort(403, 'code does not exist');

        $org = Organization::where('slug', '=', $slug)->first();
        $org_id = $org->id;
        return view('organization.receivers.form', compact('org', 'code', 'org_id', 'receiver'));
    }

    public function org_invite_form(Receiver $receiver, $slug)
    {
        $org = Organization::where('slug', '=', $slug)->first();
        return view('organization.receivers.inviteform', compact('org'));
    }

    public function org_confirm($slug, $code)
    {
        $receiver = Receiver::where('if_in_group', '=', $code)
                        ->first();

        if ( empty($receiver) )
            return abort(403, 'code does not exist');

        $org = Organization::where('slug', '=', $slug)->first();
        $org_id = $org->id;

        Receiver::where('if_in_group', '=', $code)->update([
            'org_id' => $org_id,
            'status' => 200,
            'if_in_group' => null
        ]);

        return view('organizationVerificated');
        // return view('organization.receivers.form', compact('org', 'code', 'org_id', 'receiver'));
    }

    public function org_store(Request $request, $org)
    {
        $org_info = Organization::where('slug', '=', $org)->first();
        $validated = $request->validate([
            'email' => 'required',
        ]);

        if ( Str::contains($request->email, '+') )
            return Redirect::back()->withErrors(['error' => 'The character "+" is not allowed in email address.']);

        if ( !empty(Receiver::where('email', '=', $request->email)->where('org_id', '=', null)->first()) ) {
            $code = rand(000000, 999999);
            $receiver = Receiver::where(['email' => $request->email])->update([
                'org_id' => $org_info->id,
                'if_in_group' => $code,
                'status' => 0,
            ]);
            $data = [
                'email' => $validated['email'],
                'code' => $code,
                'slug' => $org_info->slug,
            ];
            Mail::send('emails.organization.old-receiver-invite', $data, function($messages) use ($validated) {
                $messages->to( $validated['email'] );
                $messages->subject('Invite to organization');
            });
        } elseif ( empty(Receiver::where('email', '=', $request->email)->first()) ) {
            $code = Str::random(16);
            $data = [
                'email' => $validated['email'],
                'code' => $code,
                'slug' => $org_info->slug,
            ];
            Mail::send('emails.organization.receiver-invite', $data, function($messages) use ($validated) {
                $messages->to( $validated['email'] );
                $messages->subject('Invite to organization');
            });

            // Return the validated form data
            $receiver = Receiver::create([
                'email' => $request->email,
                'org_id' => $org_info->id,
                'first_name' => ' ',
                'last_name' => ' ',
                'password' => $code,
                'status' => 0
            ]);

            Mail::send('emails.organization.receiver-invite', $data, function($messages) use ($validated) {
                $messages->to( $validated['email'] );
                $messages->subject('Invite to organization');
            });

            $responseCode = Response::HTTP_OK;
            $response = ['success' => 'Passed data were successfully saved'];
        }
        else {
            return Redirect::back()->withErrors(['error' => 'You cannot invite someone who is already in the organization']);
        }

        return redirect(route('organizationreceivers.index', $org_info->slug));
    }

    public function org_show($slug, $id)
    {
        $receiver = Receiver::where('id', '=', $id)->first();
        $link = URL::to('/organization/' . $slug . '/receivers');
        $org = Organization::where('slug', '=', $slug)->first();
        return view('organization.receivers.show', compact('receiver', 'org'));
    }

    public function org_block($slug, $id)
    {
        $receiver = Receiver::where('id', '=', $id)->update(['status' => 10]);

        return redirect(route('organizationreceivers.index', $slug));
    }

    public function org_unblock($slug, $id)
    {
        $receiver = Receiver::where('id', '=', $id)->update(['status' => 200]);

        return redirect(route('organizationreceivers.index', $slug));
    }

    public function org_invite_auth(Request $request, $org_id, $code)
    {
        $inputs = [
            'password' => $request->password,
        ];

        $rules = [
            'password' => [
                'required',
                'string',
                'min:10',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/'
            ],
        ];

        $validation = Validator::make( $inputs, $rules );

        if ( $validation->fails() ) {
            return redirect('organization/'.$org_id.'/receivers/invite/'.$code)
                ->withErrors('Password should contain 10 characters: 1 capital letter, 1 non-capital letter and 1 number.')
                ->withInput();
        }

        if ( $request->password != $request->repeat_password )
            return redirect('organization/'.$org_id.'/receivers/invite/'.$code)
                ->withErrors('Password mismatch.')
                ->withInput();

        $receiver_info = Receiver::where('password', '=', $code)
        ->where('status', '=', 0)
        ->first();

        if ( empty($receiver_info) )
            return abort(403, 'code does not exist');

        $org_info = Organization::where('slug', '=', $org_id)->first();

        if ( isset($receiver_info) && isset($org_info) )
        Receiver::where(['id' => $receiver_info->id])->update([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'password'   => Hash::make($request->password),
                'status'     => 200,
            ]);


        $responseCode = Response::HTTP_OK;
        $response = ['success' => 'Passed data were successfully saved'];

        return view('organizationVerificated');
        // return response()->json($response, $responseCode);
    }

    public function org_update(Request $request, $org_id)
    {
        $org_info = Organization::where('slug', '=', $org_id)->first();

        Receiver::where(['id' => $request->id])->update($request->all());

        $responseCode = Response::HTTP_OK;
        $response = ['success' => 'Passed data were successfully saved'];

        return redirect(route('organizationreceivers.index', $org_info->slug));
        return response()->json($response, $responseCode);
    }

    public function org_remove($slug, $id)
    {
        $receiver = Receiver::where([
            'id' => $id
        ])->update([
            'status' => 0,
            'org_id' => null,
            'if_in_group' => null
            ]);

        return redirect(route('organizationreceivers.index', $slug));
    }

    public function org_destroy($slug, $id)
    {
        $deleted = Receiver::destroy($id);

        $response = [];

        return redirect(route('organizationreceivers.index', $slug));
    }
}
