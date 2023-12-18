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
        $department= Department::create($validatedData);

        return response()->json($department);
    }

    public function update(Department $department, Request $request){
        $validatedData = $request->validate([
            'name' => 'string',
            'short' => 'string',
            'faculty_id' => 'exists:faculties,id',
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
    public function restore(Department $department){
        $department->restore();
        return response()->json(['message' => 'Úspešne obnovený záznam']);
    }

    public function forceDelete(Department $department)
    {
        $department->forceDelete();
        return response()->json([
            'message' => 'Úspešne odstránený záznam',
        ]);
    }
    public function indexDeleted()
    {
        $departments = Department::onlyTrashed()->paginate(20);

        return response([
            'items' => $departments->items(),
            'prev_page_url' => $departments->previousPageUrl(),
            'next_page_url' => $departments->nextPageUrl(),
            'last_page' => $departments->lastPage(),
            'total' => $departments->total()
        ]);
    }
}
