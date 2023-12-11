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
            'short' => 'required|string',
            'department_id' => 'required|exists:departments,id',
        ]);
        $major= Major::create($validatedData);

        return response()->json($major);
    }

    public function update(Major $major, Request $request){
        $validatedData = $request->validate([
            'name' => 'string',
            'short' => 'string',
        ]);
            $major->fill($validatedData);
            $major->save();

            return response()->json($major);
    }

    public function destroy(Major $major){
        $major->delete();

        return response()->json([
            'message' => 'Faculty deleted successfully.',
        ]);
    }
}
