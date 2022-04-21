<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\organizationgroups;
use App\Models\PushNotification;
use App\Models\Receiver;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/mobile/groupList",
     * summary="Get Group List",
     * operationId="groupList",
     * tags={"Mobile"},
     *
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *  @OA\JsonContent(
     *        @OA\Property(property="org_name", type="string", example="Test"),
     *        @OA\Property(property="group_name", type="string", example="test"),
     *        @OA\Property(
     *           property="users",
     *           type="array",
     *           collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"id": 2, "first_name": "Test", "last_name": "Test2", "email": "valeriy.env@gmail.com", "photo": null, "verificate_code": null, "api_token": null, "forgot_pass": null, "status": "Blocked", "org_id": 59, "kyc_status": null, "if_in_group": 22, "firebase_token": null},
     *              )
     *        )
     *     )
     *     )
     * )
    */

    public function groupList()
    {
      $groupInfo = null;
      $list = organizationgroups::all();

      // org name
      $i = 0;
      foreach ( $list as $group ):
        $org = Organization::find($group->org_id);
        if ( !empty($org) ) {
            $groupInfo[$i]['orgName'] = $org->name;
            // group name
            $groupInfo[$i]['groupName'] = $group->name;

            // users
            $user_ids = null;
            if ( !empty($group->users['group']['receivers']) ) {
                $user_ids = $group->users['group']['receivers'];

                foreach ( $user_ids as $user_id ):
                    $user = Receiver::where('id', $user_id)->first();
                    if ( isset($user) ) {
                        $groupInfo[$i]['users'][] = $user;
                    }
                endforeach;
            }
            if ( empty($groupInfo[$i]['users']) )
                $groupInfo[$i]['users'][] = null;

            $i += 1;
        }
      endforeach;
      return response()->json($groupInfo, 200);
    }

    /**
     * @OA\Get(
     * path="/api/mobile/getNotifications",
     * summary="Get Notifications List",
     * operationId="getNotifications",
     * tags={"Mobile"},
     *
     * @OA\Response(
     *    response=200,
     *    description="Success. List of notifications",
     *     )
     * )
    */

    public function getNotifications()
    {
        $list = PushNotification::all();

      // org name
      $i = 0;
      foreach ( $list as $notification ):
        $notificationInfo[$i]['title'] = $notification['title'];
        $notificationInfo[$i]['content'] = $notification['content'];
        $i += 1;
      endforeach;
        return response()->json($notificationInfo, 200);
    }
}

