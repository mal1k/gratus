<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Tipper;
use App\Models\Transactions;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class TransactionsController extends Controller
{
    public function index($name, $model = null, $user_id = null)
        {
            $user = null;
            $transactions = null;

            if ( $model == 'Tipper' ) {
                $payment_role = 'Receiver';
                $find_user_role = 'tipper_id';
            }
            else {
                $model = 'Receiver';
                $payment_role = 'Tipper';
                $find_user_role = 'receiver_id';
            }

            $org = Organization::where('slug', '=', $name)->first();

            if ( $model ) {
                $model = '\App\Models\\'.$model;
                $user = $model::where(['id' => $user_id])->first();

                if ( !empty($user->id) )
                    $transactions = Transactions::where('org_id', '=', $org->id)
                        ->where([$find_user_role => $user->id])
                        ->orderBy('id', 'desc')
                        ->paginate(15);
            } else
                $transactions = Transactions::where('org_id', '=', $org->id)
                    ->orderBy('id', 'desc')
                    ->paginate(15);

            return view('organization.transactions.dashboard', compact('org', 'transactions', 'user', 'payment_role'));

            return response()->json([
                'items' => $transactions->getCollection()->map(function($item) {
                    return $item->getAttributesForTable();
                }),
                'currentPage' => $transactions->currentPage(),
                'lastPage' => $transactions->lastPage(),
                'attrnames' => $this->getAttributesAsKeys($transactions->getCollection())
            ]);
        }

    public function destroy($slug, $id)
        {
            $deleted = Transactions::destroy($id);

            $response = [];

            return redirect(route('organizationtransactions.index', $slug));

            if ($deleted) {

                /**
                 * @return Illuminate\Pagination\LengthAwarePaginator
                 */
                $pagination = Transactions::paginate(15);

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
            $tippers = Transactions::where('transaction_id', 'like', "%$searchable%")
                ->orWhere('amount', 'like', "%$searchable%")
                ->orWhere('status', 'like', "%$searchable%")
                ->limit(15)
                ->get();
            return response()->json($tippers);
        }


    // private function getAttributesAsKeys(Collection $collection)
    // {
    //     $model = $collection->first();
    //     return $model ? $model->getAttributeNamesForTable() : null;
    // }

    // private function getValidatedArray($array) : array
    // {
    //     $validatedString = '';

    //     foreach ($array as $key => $value) {
    //         $validatedString .= $key .'='. $value .'&';
    //     }

    //     parse_str($validatedString, $validatedArray);

    //     $validatedArray['receiver_data']['is_kyc_passed'] = (bool) $validatedArray['receiver_data']['is_kyc_passed'];

    //     return $validatedArray;
    // }
}
