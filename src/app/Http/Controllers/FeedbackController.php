<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Feedback;
use App\Models\Practice;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Practice $practice)
    {
        if(auth()->user()->role->role === "Študent") {
            if($practice->user_id != auth()->id()) {
                return response ("Forbidden", 403);
            }
        }

        if(auth()->user()->role->role === "Zástupca firmy") {
            if($practice->companyEmployee->id != auth()->user()->companyEmployee->id) {
                return response ("Forbidden", 403);
            }
        }

        return response($practice->feedback->load(["user"]));
    }

    public function store(Request $request, Practice $practice)
    {
        if(auth()->user()->role->role === "Študent") {
            if($practice->user_id != auth()->id()) {
                return response ("Forbidden", 403);
            }
        }

        if(auth()->user()->role->role === "Zástupca firmy") {
            if($practice->companyEmployee->id != auth()->user()->companyEmployee->id) {
                return response ("Forbidden", 403);
            }
        }

        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);
        $feedback = Feedback::create([...$validatedData, 'user_id' => auth()->user()->id, 'practice_id' => $practice->id]);
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
            'body' => 'required|string',
        ]);
            $feedback->fill($validatedData);
            $feedback->save();

            return response()->json($feedback);
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
    public function restore(Feedback $feedback){
        $feedback->restore();
        return response()->json(['message' => 'Úspešne obnovený záznam']);
    }

    public function forceDelete(Feedback $feedback)
    {
        $feedback->forceDelete();
        return response()->json([
            'message' => 'úspešne odstránený záznam',
        ]);
    }
    public function indexDeleted()
    {
        $feedbacks = Feedback::onlyTrashed()->paginate(20);

        return response([
            'items' => $feedbacks->items(),
            'prev_page_url' => $feedbacks->previousPageUrl(),
            'next_page_url' => $feedbacks->nextPageUrl(),
            'last_page' => $feedbacks->lastPage(),
            'total' => $feedbacks->total()
        ]);
    }

}
