<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Practice;
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
    public function getCommentsByPracticeId(int $practiceId)
    {
        $practice = Practice::find($practiceId);

        return $practice->comments;
    }

    public function store(Request $request)
    {
        $coment = Comment::create($request->all());

        return response()->json($coment,201);

    }

    public function update(Comment $comment, Request $request)
    {
        $comment = Comment::find($comment->id);

        if ($comment->user_id !== auth()->user()->id && auth()->user()->role->name !== 'Admin') {
            return response("Forbidden", 403);
        } else {
            $comment->fill($request->all());
            $comment->save();

            return response()->json($comment);
        }
    }
    public function getCommentsByUserId(int $userId)
    {
        $comments = Comment::where('user_id', $userId)->get();

        return $comments;
    }
    public function destroy(Comment $comment)
    {
        $user = auth()->user();

        // Ak je používateľ admin, môže vymazať akýkoľvek komentár.
        if ($user->role->name === 'Admin') {
            return $comment->delete();
        }

        // Ak je používateľ študent, môže vymazať iba komentár, ktorý napísal.
        if ($user->role->name === 'Študent' && $comment->user_id === $user->id) {
            return $comment->delete();
        }

        // Ak je používateľ poverený pracovník pracoviska, môže vymazať iba komentár, ktorý napísal.
        if ($user->role->name === 'Poverený pracovník pracoviska' && $comment->user_id === $user->id) {
            return $comment->delete();
        }

        // Ak je používateľ vedúci pracoviska, môže vymazať iba komentár, ktorý napísal.
        if ($user->role->name === 'Vedúci pracoviska' && $comment->user_id === $user->id) {
            return $comment->delete();
        }

        // Používateľ nemá povolenie vymazať komentár.
        return response("Forbidden", 403);
    }
}
