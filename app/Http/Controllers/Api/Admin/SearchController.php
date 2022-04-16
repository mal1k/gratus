<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Email,
    Organization,
    organizationgroups,
    PushNotification,
    Receiver,
    Schedule,
    Tipper,
    Transactions,
    User
};

class SearchController extends Controller
{
    /**
     * Find a searchable getting from an organization
     */
    public function organizations(Request $request)
    {
        $searchable = $request->input('search');
        $org = Organization::where('name', 'like', "%$searchable%")
            ->limit(15)
            ->get();

        $i = 0;
        foreach ( $org as $org ):
            $org_list[$i]['id'] = $org->id;
            $org_list[$i]['name'] = $org->name;
            $org_list[$i]['email'] = $org->email;
            $org_list[$i]['status'] = $org->status;
            $i += 1;
        endforeach;

        if ( empty($org_list) )
            return response()->json(array());

        return response()->json($org_list);

    }

    /**
     * Find a searchable getting from an provider
     */
    public function receivers(Request $request)
    {
        $searchable = $request->input('search');

        if ( str_contains($searchable, '/') )
        {
            $results = null;
            $receivers = null;

            $search_part = explode("/", $searchable);
            $org_part = $search_part[0];
            $group_part = $search_part[1];

            $organization = Organization::where('name', 'like', "%$org_part%")->first();

            if ( !empty( json_decode($organization) ) )
                $groups = organizationgroups::where('name', 'like', "%$group_part%")
                        ->where(['org_id' => $organization->id])
                        ->get();

            if ( !empty(json_decode($groups)) ) {
                $receivers_db_list = Receiver::where('org_id', '=', $organization->id);

                $receivers_db_list->where(function ($receivers_db_list) use ($groups) {
                    foreach ($groups as $group) {
                        $receivers_db_list->orWhere('if_in_group', '=', $group->id);
                    }
                });

                $receivers = $receivers_db_list->limit(15)->get();
            }
        }
        else {
            $receivers = Receiver::where('first_name', 'like', "%$searchable%")
                ->orWhere('last_name', 'like', "%$searchable%")
                ->orWhere('email', 'like', "%$searchable%");

            if ( str_starts_with( strtolower($searchable), 'blocked') )
                $receivers->orWhere('status', '=', 10);

            if ( str_starts_with( strtolower($searchable), 'not verified') )
                $receivers->orWhere('status', '=', 0);

            if ( str_starts_with( strtolower($searchable), 'active') )
                $receivers->orWhere('status', '=', 200);

            $receiver_group = Organization::where('name', 'like', "%$searchable%");
            $receiver_group->where(function ($receiver_group) use ($receivers) {
                foreach ($receivers as $receiver) {
                    $receiver_group->orwhere('org_id', '=', $receiver->org_id);
                }
            });
            $results = $receiver_group->get();

            $receivers->orWhere(function ($receivers) use ($results) {
                foreach ($results as $result) {
                    $receivers->orWhere('org_id', '=', $result->id);
                }
            });

            $receivers = $receivers->limit(15)->get();
        }


        $i = 0;
        $org_name = null;
        $group_name = null;

        if ( !empty($receivers) )
            foreach ( $receivers as $receiver ):
                if ( $receiver->if_in_group ) {
                    $org = Organization::where(['id' => $receiver->org_id])->first();
                    if ( $org )
                        $org_name = $org->name;
                }
                else
                    $org_name = null;

                if ( $receiver->if_in_group ) {
                    $group = organizationgroups::where(['id' => $receiver->if_in_group])->first();
                    if ( $group )
                        $group_name = $group->name;
                }
                else
                    $group_name = null;

                $receivers_list[$i]['id'] = $receiver->id;
                $receivers_list[$i]['first_name'] = $receiver->first_name;
                $receivers_list[$i]['last_name'] = $receiver->last_name;
                $receivers_list[$i]['email'] = $receiver->email;
                $receivers_list[$i]['organization'] = $org_name;
                $receivers_list[$i]['group'] = $group_name;
                $receivers_list[$i]['status'] = $receiver->status;
                $i += 1;
            endforeach;

        if ( empty($receivers_list) )
            return response()->json(array());

        return response()->json($receivers_list);
    }

    /**
     * Find a searchable getting from an user
     */
    public function users(Request $request)
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
     * Find a searchable getting from an emails
     */
    public function mails(Request $request)
    {
        $searchable = $request->input('search');
        $mails = Email::where('title', 'like', "%$searchable%")
            ->orWhere('content', 'like', "%$searchable%")
            ->limit(15)
            ->get();
        return response()->json($mails);
    }

    /**
     * Find a searchable getting from an pushs
     */
    public function pushs(Request $request)
    {
        $searchable = $request->input('search');
        $pushs = PushNotification::where('title', 'like', "%$searchable%")
            ->orWhere('content', 'like', "%$searchable%")
            ->limit(15)
            ->get();
        return response()->json($pushs);
    }

    public function tippers(Request $request)
    {
        $searchable = $request->input('search');
        $users = Tipper::where('first_name', 'like', "%$searchable%")
            ->orWhere('last_name', 'like', "%$searchable%")
            ->orWhere('email', 'like', "%$searchable%")
            ->limit(15)
            ->get();

        $i = 0;
        foreach ( $users as $user ):
            $users_list[$i]['id'] = $user->id;
            $users_list[$i]['first_name'] = $user->first_name;
            $users_list[$i]['last_name'] = $user->last_name;
            $users_list[$i]['email'] = $user->email;
            $users_list[$i]['status'] = $user->status;
            $i += 1;
        endforeach;

        if ( empty($users_list) )
            return response()->json(array());

        return response()->json($users_list);
    }

    public function transactions(Request $request)
    {
        $searchable = $request->input('search');
        $transactions_list = null;

        $transactions = Transactions::where('transaction_id', 'like', "%$searchable%")
            ->orWhere('amount', 'like', "%$searchable%")
            ->orderBy('id', 'desc')
            ->limit(15)
            ->get();
        $i = 0;

        foreach ( $transactions as $transaction ):
            $tipper_user = Tipper::where(['id' => $transaction->tipper_id])->first();
            $receiver_user = Receiver::where(['id' => $transaction->receiver_id])->first();

            isset($receiver_user->org_id ) ? $org = Organization::where(['id' => $receiver_user->org_id])->first() : $org = null;
            isset($receiver_user->if_in_group ) ? $group = organizationgroups::where(['id' => $receiver_user->if_in_group])->first() : $group = null;

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

        if ( empty($transactions_list) )
            return response()->json(array());

        return response()->json($transactions_list);
    }

    public function schedule(Request $request)
    {
        $searchable = $request->input('search');

        if ( !str_contains($searchable, ' ') ) {
            $receivers = Receiver::where('first_name', 'like', "%$searchable%")
                ->orWhere('last_name', 'like', "%$searchable%")
                ->get();
        } else {
            $search_part = explode(" ", $searchable);
            $name_part = $search_part[0];
            $surname_part = $search_part[1];

            $receivers = Receiver::where('first_name', 'like', "%$name_part%")
                ->where('last_name', 'like', "%$surname_part%")
                ->get();
        }

        $schedule = Schedule::where('user_id', 'like', "%$searchable%");

        if ( !empty($receivers) )
            foreach ( $receivers as $receiver )
                    $schedule->orWhere('user_id', '=', $receiver->id);

        $schedule = $schedule->limit(15)->get();

        if ( empty($schedule) )
            return response()->json(array());

        if ( !empty(json_decode($schedule)) ) {
            $i = 0;
            foreach ( $schedule as $pagination_item ):
                // items create
                  $org_name = null;
                  $full_name = null;
                  $org = null;
                  $punch_out = null;

                  $class = 'App\Models\\';
                  if ( empty($pagination_item->model) )
                      $class .= 'Receiver';
                  else
                      $class .= $pagination_item->model;
                  $user = $class::where('id', $pagination_item->user_id)->first();

                  if (!empty($user)) {
                      $full_name = $user->first_name . ' ' . $user->last_name;

                      if ( !empty( $user->org_id ) )
                          $org = Organization::where('id', $user->org_id)->first();

                      if ( !empty($org) )
                       $org_name = $org->name;
                  }

                  $punch_in = date('d/m/Y H:i', strtotime($pagination_item->punch_in) );
                  if ( !empty($pagination_item->punch_out) )
                      $punch_out = date('d/m/Y H:i', strtotime($pagination_item->punch_out) );

                  $schedule_list[$i]['id'] = $pagination_item->id;
                  $schedule_list[$i]['fullname'] = $full_name;
                  $schedule_list[$i]['organization'] = $org_name;
                  $schedule_list[$i]['model'] = $pagination_item->model;
                  $schedule_list[$i]['punch_in'] = $punch_in;
                  $schedule_list[$i]['punch_out'] = $punch_out;
                  $i += 1;
              endforeach;
            return response()->json($schedule_list);
        } else
            return response()->json(array());



    }
}
