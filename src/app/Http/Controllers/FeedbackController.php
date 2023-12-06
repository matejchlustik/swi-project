<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Feedback;
use App\Models\Practice;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedback = Feedback::paginate(20);

        return response([
            'items' => $feedback->items(),
            'prev_page_url' =>$feedback->previousPageUrl(),
            'next_page_url' => $feedback->nextPageUrl(),
            'last_page' =>$feedback->lastPage(),
            'total' => $feedback->total()
        ]);
    }
    public function getFeedbacksByPracticeId(Practice $practice)
    {
        $feedback = Feedback::where("practice_id", $practice->id)->get();
        return response($feedback);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'body' => 'required',
            'practice_id' => 'required|exists:practices,id',
        ]);
        $feedback = Feedback::create([...$validatedData, 'user_id' => auth()->user()->id]);
        return response()->json($feedback);

    }

    public function update(Feedback $feedback, Request $request)
    {
        if (auth()->user()->role->role !== "Admin") {
            if (auth()->user()->id !== $feedback->user_id) {
                return response("Forbidden", 403);
            }
        }
        $validatedData = $request->validate([
            'body' => 'required',
        ]);
            $feedback->fill($validatedData);
            $feedback->save();

            return response()->json($feedback);
    }

    public function getFeedbacksByUserId(User $user)
    {
        $feedback = Feedback::where('user_id', $user->id)->get();

        return response($feedback);
    }

    public function destroy(Feedback $feedback)
    {
        if (auth()->user()->role->role !== "Admin") {
        if (auth()->user()->id !== $feedback->user_id) {
                return response("Forbidden", 403);
        }
        }
        $feedback->delete();
        return response()->json(['message' => 'Feedback deleted successfully.']);
    }

}
