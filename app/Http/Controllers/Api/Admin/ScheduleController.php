<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\organizationgroups;
use App\Models\Receiver;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the user resource.
     */
    public function schedule_index($name)
    {
        $org = Organization::where('slug', '=', $name)->first();

        $receivers_list = Receiver::where('org_id', '=', $org->id)->get();

        $find_receivers = Schedule::where(function ($find_receivers) use ($receivers_list) {
            foreach ($receivers_list as $receiver) {
                $find_receivers->orwhere('user_id', '=', $receiver->id);
            }
        });

        $receivers = $find_receivers->orderBy('id', 'desc')->paginate(15);

        return view('organization.schedule.dashboard', compact('org', 'receivers'));
    }

    // public function index()
    // {
    //     /**
    //      * @return Illuminate\Pagination\LengthAwarePaginator
    //      */
    //     $pagination = Schedule::paginate(15);

    //     return response()->json([
    //         'items' => $pagination->getCollection()->map(function($item) {
    //             $class = 'App\Models\\';
    //             $class .= $item->model;
    //             $user = $class::where(['id' => $item->user_id])->first();
    //             return [
    //                 'id'         => $item->id,
    //                 'username'   => $user->first_name . ' ' . $user->last_name,
    //                 'punch_in'   => $item->punch_in,
    //                 'punch_out'  => $item->punch_out
    //             ];
    //         }),
    //         'currentPage' => $pagination->currentPage(),
    //         'lastPage' => $pagination->lastPage(),
    //         'attrnames' => $this->getAttributesAsKeys($pagination->getCollection())
    //     ]);
    // }

    public function index()
    {
        $pagination = Schedule::orderBy('id', 'asc')->paginate(15);
        $schedule_list = null;
        $i = 0;

        foreach ( $pagination as $pagination_item ):
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

        if ( empty($schedule_list) )
            return response()->json(array());

        return response()->json([
            'items' => $schedule_list,
            'currentPage' => $pagination->currentPage(),
            'lastPage' => $pagination->lastPage(),
            'attrnames' => $this->getAttributesAsKeys($pagination->getCollection())
        ]);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $deleted = Schedule::destroy($id);

        $response = [];

        if ($deleted) {

            /**
             * @return Illuminate\Pagination\LengthAwarePaginator
             */
            $pagination = Schedule::paginate(15);

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
        $users = Schedule::where('model', 'like', "%$searchable%")
            ->orWhere('last_name', 'like', "%$searchable%")
            ->orWhere('user_id', 'like', "%$searchable%")
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
