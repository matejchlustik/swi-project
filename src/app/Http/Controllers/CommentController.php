<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Practice;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::paginate(20);

        return response([
            'items' => $comments->items(),
            'prev_page_url' =>$comments->previousPageUrl(),
            'next_page_url' => $comments->nextPageUrl(),
            'last_page' =>$comments->lastPage(),
            'total' => $comments->total()
        ]);
    }
    public function getCommentsByPracticeId(Practice $practice)
    {
        $practices = Practice::find($practice->id);

        return response($practices);
    }

    public function store(Request $request)
    {
            $validatedData = $request->validate([
                'body' => 'required',
                'practice_id' => 'required|exists:practices,id',
                'user_id' => auth()->user()->id
            ]);
            $comment = Comment::create($validatedData);
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
            'body' => 'required',
        ]);
            $comment->fill($validatedData);
            $comment->save();

            return response()->json($comment);
    }
    public function getCommentsByUserId(User $user)
    {
        $comments = Comment::where('user_id', $user->id)->get();

        return response($comments);
    }
    public function destroy(Comment $comment)
    {
        if (auth()->user()->role->role !== "Admin") {
        if (auth()->user()->id !== $comment->user_id) {
                return response("Forbidden", 403);
        }
        }
        $comment->delete();
        return response()->json(['message' => 'Company deleted successfully.',]);
    }
}
