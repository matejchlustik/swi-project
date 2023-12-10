<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(){
        $departments = Department::paginate(20);

        return response([
            'items' => $departments ->items(),
            'prev_page_url' =>$departments ->previousPageUrl(),
            'next_page_url' => $departments ->nextPageUrl(),
            'last_page' =>$departments ->lastPage(),
            'total' => $departments ->total()
        ]);
    }

    public function show(Department $department){
        return response()->json($department);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string',
            'short' => 'required|string',
            'faculty_id' => 'required|exists:faculties,id',
        ]);
        $department= Department::create($request->all());

        return response()->json($department);
    }

    public function update(Department $department, Request $request){
        $validatedData = $request->validate([
            'name' => 'string',
            'short' => 'string',
        ]);
            $department->fill($validatedData);
            $department->save();

            return response()->json($department);
    }

    public function destroy(Department $department){
        $department->delete();

        return response()->json([
            'message' => 'Faculty deleted successfully.',
        ]);
    }
}
