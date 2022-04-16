<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\organizationgroups;
use App\Models\Receiver;
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

class TransactionsAdminController extends Controller
{
    public function index()
        {
            $transactions = Transactions::orderBy('id', 'desc')->paginate(15);
            $transactions_list = null;
            $i = 0;

            foreach ( $transactions as $transaction ):
                $org = Organization::where(['id' => $transaction->org_id])->first();
                $receiver_user = Receiver::where(['id' => $transaction->receiver_id])->first();
                $tipper_user = Tipper::where(['id' => $transaction->tipper_id])->first();

              // items create
                $transactions_list[$i]['transaction_id'] = $transaction->transaction_id;
                isset($tipper_user->first_name) && isset($tipper_user->last_name) ? $transactions_list[$i]['user_id'] = $tipper_user->first_name . ' ' . $tipper_user->last_name : $transactions_list[$i]['user_id'] = '';
                isset($receiver_user->first_name) && isset($receiver_user->last_name) ? $transactions_list[$i]['receiver_id'] = $receiver_user->first_name . ' ' . $receiver_user->last_name : $transactions_list[$i]['receiver_id'] = '';
                isset($org->name) ? $transactions_list[$i]['org'] = $org->name : $transactions_list[$i]['org'] = '';
                $transactions_list[$i]['status'] = $transaction->status;
                $transactions_list[$i]['amount'] = $transaction->amount;
                $transactions_list[$i]['date'] = date('d/m/Y', strtotime($transaction->created_at) );
                $transactions_list[$i]['time'] = date('H:i', strtotime($transaction->created_at) );
                $i += 1;
            endforeach;

            return response()->json([
                'items' => $transactions_list,
                'currentPage' => $transactions->currentPage(),
                'lastPage' => $transactions->lastPage(),
                'attrnames' => $this->getAttributesAsKeys($transactions->getCollection())
            ]);
        }

    public function search(Request $request)
    {
        $searchable = $request->input('search');
        $transactions_list = null;
        $transactions = Transactions::where('transaction_id', 'like', "%$searchable%")
            // ->orWhere('last_name', 'like', "%$searchable%")
            // ->orWhere('email', 'like', "%$searchable%")
            ->limit(15)
            ->get();

        $i = 0;
        foreach ( $transactions as $transaction ):
            $transactions_list[$i]['transaction_id'] = $transaction->transaction_id;
            $transactions_list[$i]['org_id'] = $transaction->org_id;
            $transactions_list[$i]['receiver_id'] = $transaction->receiver_id;
            $transactions_list[$i]['user_id'] = $transaction->user_id;
            $transactions_list[$i]['amount'] = $transaction->amount;
            $i += 1;
        endforeach;

        if ( empty($transactions_list) )
            return response()->json(array());

        return response()->json($transactions_list);
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
