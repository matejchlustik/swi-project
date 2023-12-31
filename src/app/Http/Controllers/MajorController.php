<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index(){
        $majors = Major::paginate(20);

        return response([
            'items' => $majors  ->items(),
            'prev_page_url' =>$majors  ->previousPageUrl(),
            'next_page_url' => $majors  ->nextPageUrl(),
            'last_page' =>$majors  ->lastPage(),
            'total' => $majors  ->total()
        ]);
    }

    public function show(Major $major){
        return response()->json($major);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string',
            'department_id' => 'required|exists:departments,id',
        ]);
        $major= Major::create($validatedData);

        return response()->json($major);
    }

    public function update(Major $major, Request $request){
        $validatedData = $request->validate([
            'name' => 'string',
            'department_id' => 'exists:departments,id',
        ]);
            $major->fill($validatedData);
            $major->save();

            return response()->json($major);
    }

    public function destroy(Major $major){
        $major->delete();

        return response()->json([
            'message' => 'Major deleted successfully.',
        ]);
    }
    public function restore(Major $major){
        $major->restore();
        return response()->json(['message' => 'úspešne obnovený záznam']);
    }

    public function forceDelete(Major $major)
    {
        $major->forceDelete();
        return response()->json([
            'message' => 'úspešne odstránený záznam',
        ]);
    }
    public function indexDeleted()
    {
        $majors = Major::onlyTrashed()->paginate(20);

        return response([
            'items' => $majors->items(),
            'prev_page_url' => $majors->previousPageUrl(),
            'next_page_url' => $majors->nextPageUrl(),
            'last_page' => $majors->lastPage(),
            'total' => $majors->total()
        ]);
    }
}
