<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comments::paginate(20);

        return response([
            'items' => $comments->items(),
            'prev_page_url' =>$comments->previousPageUrl(),
            'next_page_url' => $comments->nextPageUrl(),
            'last_page' =>$comments->lastPage(),
            'total' => $comments->total()
        ]);
    }


    public function store(Request $request)
    {
        $coment = Comments::create($request->all());

        return response()->json($coment,201);

    }

    public function update(Comments $comment, Request $request)
    {
        $comment->fill($request->all());
        $comment->save();

        return response()->json($comment);
    }

    public function destroy(Comments $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.',
        ]);
    }
}
