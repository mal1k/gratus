<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PushsController extends Controller
{
    // public function index()
    // {
    //     $pushs = PushNotification::paginate(15);
    //     return view('toReact.pushNotifications.dashboard', compact('pushs'));
    // }

    public function index()
    {
        $pagination = PushNotification::paginate(15);

        return response()->json([
            'items' => $pagination->getCollection()->map(function($item) {
                return $item->getAttributesForTable();
            }),
            'currentPage' => $pagination->currentPage(),
            'lastPage' => $pagination->lastPage(),
            'attrnames' => $this->getAttributesAsKeys($pagination->getCollection())
        ]);
    }



    public function create()
        {
            return view('toReact.pushNotifications.form');
        }

    public function store(Request $request)
        {
            $validated = $request->validate([
                'title' => 'required|max:255',
            ]);

            $pushNotification = PushNotification::create($request->all()); // create event

            return redirect()->route('pushs.index')->withSuccess('Created push notification "' . $request->title . '"');
        }

    public function edit(PushNotification $push)
    {
        return view('toReact.pushNotifications.form', compact('push'));
    }

    public function show($id)
    {
        $pushs = PushNotification::find($id);

        return response()->json($pushs);
    }

    public function update(Request $request)
        {
            $response = [];

            $email = PushNotification::where(['id' => $request->id])->update([
                'title' => $request->title,
                'content' => $request->content
            ]);

            if (! $email) {
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
            $deleted = PushNotification::destroy($id);

            $response = [];

            if ($deleted) {

                /**
                 * @return Illuminate\Pagination\LengthAwarePaginator
                 */
                $pagination = PushNotification::paginate(15);

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
        $pushs = PushNotification::where('title', 'like', "%$searchable%")
            ->orWhere('content', 'like', "%$searchable%")
            ->limit(15)
            ->get();
        return response()->json($pushs);
    }

    private function getAttributesAsKeys(Collection $collection)
    {
        $model = $collection->first();
        return $model ? $model->getAttributeNamesForTable() : null;
    }
}
