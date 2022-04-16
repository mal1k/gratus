<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the user resource.
     */
    public function index()
    {
        /**
         * @return Illuminate\Pagination\LengthAwarePaginator
         */
        $pagination = User::paginate(15);

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
     * Save a new user
     */
    public function store(UserRequest $request)
    {
        // Return the validated form data
        $validated = $request->validated();

        $code = Str::random(16);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'verificate_code' => $code,
            'password' => Hash::make($validated['password']),
            'status' => User::setStatus($validated['status']),
        ]);

        if (! $user) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response = ['serverError' => 'Something went wrong on my side. Mr. Server'];
        } else {
            $responseCode = Response::HTTP_OK;
            $response = ['success' => 'Passed data were successfully saved'];
        }

        $data = [
            'name' => $request->first_name . ' ' . $request->last_name,
            'code' => $code
        ];

        Mail::send('emails.register', $data, function($messages) use ($request) {
            $messages->to( $request['email'] );
            $messages->subject('Account Activation');
        });

        return response()->json($response, $responseCode);
    }

    /**
     * Show the specified user
     */
    public function show($id)
    {
        $user = User::find($id);

        return response()->json($user);
    }

    /**
     * Update the specified user
     */
    public function update(UserRequest $request)
    {
        // Return the validated form data
        $validated = $request->validated();

        $response = [];

        $updateableFields = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'status' => User::setStatus($validated['status']),
        ];

        if (!empty($validated['password'])) {
            $updateableFields['password'] = Hash::make($validated['password']);
        }

        $user = User::where(['id' => $validated['id']])->update($updateableFields);

        if (! $user) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response = ['serverError' => 'Something went wrong on my side. Mr. Server'];
        } else {
            $responseCode = Response::HTTP_OK;
            $response = ['success' => 'Passed data were successfully saved'];
        }

        return response()->json($response, $responseCode);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $deleted = User::destroy($id);

        $response = [];

        if ($deleted) {

            /**
             * @return Illuminate\Pagination\LengthAwarePaginator
             */
            $pagination = User::paginate(15);

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
     * Find a searchable getting from an user
     */
    public function search(Request $request)
    {
        $searchable = $request->input('search');
        $users = User::where('first_name', 'like', "%$searchable%")
            ->orWhere('last_name', 'like', "%$searchable%")
            ->orWhere('email', 'like', "%$searchable%")
            ->limit(15)
            ->get();
        return response()->json($users);
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
