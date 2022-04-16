<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\MotivationWord;
use Illuminate\Http\Request;

class MotivationWordsController extends Controller
{
    public function index()
    {
        $MotivationWords = MotivationWord::paginate(15);
        return view('toReact.motivationWords.dashboard', compact('MotivationWords'));
    }

    public function create()
        {
            return view('toReact.motivationWords.form');
        }

    public function store(Request $request)
        {
            $validated = $request->validate([
                'title' => 'required|max:255',
            ]);

            $MotivationWord = MotivationWord::create($request->all()); // create event

            return redirect()->route('motivation-words.index')->withSuccess('Created motivation word "' . $request->title . '"');
        }

    public function edit(MotivationWord $MotivationWord)
    {
        return view('toReact.motivationWords.form', compact('MotivationWord'));
    }

    public function update(Request $request, MotivationWord $MotivationWord)
        {
            $MotivationWord->update($request->all()); // update pushNotification

            return redirect()->route('motivation-words.index')->withSuccess('Updated motivation word "' . $request->title . '"');
        }

    public function destroy(MotivationWord $MotivationWord)
        {
            $MotivationWord->delete();

            return redirect()->route('motivation-words.index')->withSuccess('Deleted motivation word "' . $MotivationWord->title . '"');

        }
}
