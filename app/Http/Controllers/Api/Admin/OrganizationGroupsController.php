<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\organizationgroups;
use App\Models\Receiver;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class OrganizationGroupsController extends Controller
{
    public function index($id = null)
    {
        if ( empty($id) )
            $organizationgroups = organizationgroups::paginate(15);
        else
            $organizationgroups = organizationgroups::where('org_id', '=', $id)
                ->paginate(15);
        $organizationgroups_list = null;
        $i = 0;

        foreach ( $organizationgroups as $organizationgroup ):
            $users = null;
            if (isset($organizationgroup->users['group']['receivers']))
                $users = $organizationgroup->users['group']['receivers'];

            $user_list = null;
            if ( isset($users) )
              foreach ( $users as $userID ) {
                  $user = Receiver::where(['id' => $userID])->first();
                  if ( isset($user->first_name) && isset($user->last_name) )
                    $user_list[] = $user->first_name . ' ' . $user->last_name;
              }


          // items create
            $organizationgroups_list[$i]['id'] = $organizationgroup->id;
            $organizationgroups_list[$i]['name'] = $organizationgroup->name;
            if ( isset($user_list) )
                $organizationgroups_list[$i]['users'] = implode('; ', $user_list);
            else
                $organizationgroups_list[$i]['users'] = null;
            $organizationgroups_list[$i]['date'] = date('d/m/Y', strtotime($organizationgroup->created_at) );
            $organizationgroups_list[$i]['time'] = date('H:i', strtotime($organizationgroup->created_at) );
            $i += 1;
        endforeach;

        if ( empty($organizationgroups_list) )
            return response()->json(array());

        return response()->json([
            'items' => $organizationgroups_list,
            'currentPage' => $organizationgroups->currentPage(),
            'lastPage' => $organizationgroups->lastPage(),
            'attrnames' => $this->getAttributesAsKeys($organizationgroups->getCollection())
        ]);
    }

    public function show($id)
    {
        if ( empty($id) )
            $organizationgroups = organizationgroups::paginate(15);
        else
            $organizationgroups = organizationgroups::where('org_id', '=', $id)
                ->paginate(15);
        $organizationgroups_list = null;
        $i = 0;

        foreach ( $organizationgroups as $organizationgroup ):
            $users = null;
            if (isset($organizationgroup->users['group']['receivers']))
                $users = $organizationgroup->users['group']['receivers'];

            $user_list = null;
            if ( isset($users) )
              foreach ( $users as $userID ) {
                  $user = Receiver::where(['id' => $userID])->first();
                  if ( isset($user->first_name) && isset($user->last_name) )
                    $user_list[] = $user->first_name . ' ' . $user->last_name;
              }


          // items create
            $organizationgroups_list[$i]['id'] = $organizationgroup->id;
            $organizationgroups_list[$i]['name'] = $organizationgroup->name;
            if ( isset($user_list) )
                $organizationgroups_list[$i]['users'] = implode('; ', $user_list);
            else
                $organizationgroups_list[$i]['users'] = null;
            $organizationgroups_list[$i]['date'] = date('d/m/Y', strtotime($organizationgroup->created_at) );
            $organizationgroups_list[$i]['time'] = date('H:i', strtotime($organizationgroup->created_at) );
            $i += 1;
        endforeach;

        if ( empty($organizationgroups_list) )
            return response()->json(array());

        return response()->json([
            'items' => $organizationgroups_list,
            'currentPage' => $organizationgroups->currentPage(),
            'lastPage' => $organizationgroups->lastPage(),
            'attrnames' => $this->getAttributesAsKeys($organizationgroups->getCollection())
        ]);
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
