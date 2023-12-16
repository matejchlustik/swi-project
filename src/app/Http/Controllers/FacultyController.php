<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index(){
        $faculties = Faculty::paginate(20);

        return response([
            'items' => $faculties ->items(),
            'prev_page_url' =>$faculties ->previousPageUrl(),
            'next_page_url' => $faculties ->nextPageUrl(),
            'last_page' =>$faculties ->lastPage(),
            'total' => $faculties ->total()
        ]);
    }

    public function show(Faculty $faculty){
        return response()->json($faculty);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string',
            'short' => 'required|string',
        ]);
        $faculty= Faculty::create($validatedData);

        return response()->json($faculty);
    }

    public function update(Faculty $faculty, Request $request){
        $validatedData = $request->validate([
            'name' => 'string',
            'short' => 'string',
        ]);
            $faculty->fill($validatedData);
            $faculty->save();

            return response()->json($faculty);
    }

    public function destroy(Faculty $faculty){
        $faculty->delete();

        return response()->json([
            'message' => 'Faculty deleted successfully.',
        ]);
    }
    public function restore(Faculty $faculty){
        $faculty->restore();
        return response()->json(['message' => 'Úspešne reaktovovaný']);
    }

    public function forceDelete(Faculty $faculty)
    {
        $faculty->forceDelete();
        return response()->json([
            'message' => 'Používateľ bol úspešne odstránený.',
        ]);
    }
    public function indexDeleted()
    {
        $faculties = Faculty::onlyTrashed()->paginate(20);

        return response([
            'items' => $faculties->items(),
            'prev_page_url' => $faculties->previousPageUrl(),
            'next_page_url' => $faculties->nextPageUrl(),
            'last_page' => $faculties->lastPage(),
            'total' => $faculties->total()
        ]);
    }
}
