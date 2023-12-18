<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Practice;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Practice $practice)
    {
        if(auth()->user()->role->role === "Študent") {
            if($practice->user_id != auth()->id()) {
                return response ("Forbidden", 403);
            }
        }

        return response($practice->comments->load(["user"]));
    }


    public function store(Request $request, Practice $practice)
    {
            $validatedData = $request->validate([
                'body' => 'required|string',
            ]);
            $comment = Comment::create([...$validatedData, 'user_id' => auth()->user()->id, 'practice_id' => $practice->id]);
            return response()->json($comment);
    }

    public function update(Comment $comment, Request $request)
    {
        if (auth()->user()->role->role !== "Admin") {
            if (auth()->user()->id !== $comment->user_id) {
                return response("Forbidden", 403);
            }
        }
        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);
            $comment->fill($validatedData);
            $comment->save();

            return response()->json($comment);
    }

    public function destroy(Comment $comment)
    {
        if (auth()->user()->role->role !== "Admin") {
        if (auth()->user()->id !== $comment->user_id) {
                return response("Forbidden", 403);
        }
        }
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully.']);
    }
    public function restore(Comment $comment){
        $comment->restore();
        return response()->json(['message' => 'Úspešne obnovený záznam']);
    }

    public function forceDelete(Comment $comment)
    {
        $comment->forceDelete();
        return response()->json([
            'message' => 'úspešne odstránený záznam',
        ]);
    }
    public function indexDeleted()
    {
        $comments = Comment::onlyTrashed()->paginate(20);

        return response([
            'items' => $comments->items(),
            'prev_page_url' => $comments->previousPageUrl(),
            'next_page_url' => $comments->nextPageUrl(),
            'last_page' => $comments->lastPage(),
            'total' => $comments->total()
        ]);
    }
}
