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
use Illuminate\Support\Facades\URL;

class TipperAdminController extends Controller
{
    public function index()
        {
            $pagination = Tipper::paginate(15);

            return response()->json([
                'items' => $pagination->getCollection()->map(function($item) {
                    return $item->getAttributesForTable();
                }),
                'currentPage' => $pagination->currentPage(),
                'lastPage' => $pagination->lastPage(),
                'attrnames' => $this->getAttributesAsKeys($pagination->getCollection())
            ]);
        }

    public function store(Request $request)
        {
            $validator = $request->validate([
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|max:255'
            ]);

            if ( empty($validator->errors) )
                {
                    $responseCode = Response::HTTP_OK;
                    $response = ['success' => 'Passed data were successfully saved'];
                    $tipper = Tipper::where(['id' => $request->id])->create([
                        'first_name' => $request->first_name,
                        'last_name'  => $request->last_name,
                        'email'      => $request->email,
                        'password'   => Hash::make($request->password)
                    ]);
                    if ( $request->status == 1 )
                        Tipper::where(['id' => $request->id])->update([ 'status' => 200 ]);
                    else
                        Tipper::where(['id' => $request->id])->update([ 'status' => 0 ]);
                } else {
                    $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $response = ['success' => 'Something went wrong'];
                }
            return response()->json($response, $responseCode);
        }

    public function show($id)
        {
            $tipper = Tipper::find($id);

            return response()->json($tipper);
        }

    public function update(Request $request)
        {
            $response = [];

            $tipper = Tipper::where(['id' => $request->id])->update([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                // 'email'      => $request->email
            ]);

            if ( $request->password )
                Tipper::where(['id' => $request->id])->update([ 'password' => Hash::make($request->password) ]);

            if ( $request->status == 1 )
                Tipper::where(['id' => $request->id])->update([ 'status' => 200 ]);
            else
                Tipper::where(['id' => $request->id])->update([ 'status' => 0 ]);

            if (! $tipper) {
                $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                $response = ['serverError' => 'Something went wrong on my side. Mr. Server'];
            } else {
                $responseCode = Response::HTTP_OK;
                $response = ['success' => 'Passed data were successfully saved'];
            }

            return response()->json($response, $responseCode);
        }

    public function destroy($id)
        {
            $deleted = Tipper::destroy($id);

            $response = [];

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
            $emails = Tipper::where('title', 'like', "%$searchable%")
                ->orWhere('content', 'like', "%$searchable%")
                ->limit(15)
                ->get();
            return response()->json($emails);
        }

    private function getAttributesAsKeys(Collection $collection)
        {
            $model = $collection->first();
            return $model ? $model->getAttributeNamesForTable() : null;
        }
}
