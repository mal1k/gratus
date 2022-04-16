<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function index()
    {
        $pagination = Email::paginate(15);

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
            return view('toReact.emails.form');
        }

    public function store(Request $request)
        {
            $validated = $request->validate([
                'title' => 'required|max:255',
            ]);

            $email = Email::create($request->all()); // create event

            return redirect()->route('emails.index')->withSuccess('Created email "' . $request->title . '"');
        }

    public function edit(Email $email)
    {
        return view('toReact.emails.form', compact('email'));
    }

    public function show($id)
    {
        $email = Email::find($id);

        return response()->json($email);
    }

    public function update(Request $request)
        {
            $response = [];

            $email = Email::where(['id' => $request->id])->update([
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

    // public function destroy(Email $email)
    //     {
    //         $email->delete();

    //         return redirect()->route('emails.index')->withSuccess('Deleted email "' . $email->title . '"');

    //     }

    public function destroy($id)
        {
            $deleted = Email::destroy($id);

            $response = [];

            if ($deleted) {

                /**
                 * @return Illuminate\Pagination\LengthAwarePaginator
                 */
                $pagination = Email::paginate(15);

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
        $emails = Email::where('title', 'like', "%$searchable%")
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
