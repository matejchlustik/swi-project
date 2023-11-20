<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\PracticeRecord;
use Illuminate\Http\Request;


class PracticeController extends Controller
{

    public function store(Request $request)
    {
        $newPractice = new Practice();
        $newPractice->from = $request->input('from');
        $newPractice->to = $request->input('to');
        $newPractice->company_employee_id = $request->input('company_employee_id');
        $newPractice->department_employee_id = $request->input('department_employee_id');
        $newPractice->program_id = $request->input('program_id');
        $newPractice->contract = $request->input('contract');
        $newPractice->user_id = auth()->id();

        $newPractice->save();

        return response()->json([
            "saved practice_id" => $newPractice->id
        ]);
    }

    public function index()
    {

        $practices = [];
        if(auth()->user()->role->role === "Študent") {
            $practices = Practice::where('user_id', auth()->id())->paginate(10);
        } else if(auth()->user()->role->role === "Zástupca firmy") {
            $practices = Practice::where('company_employee_id', auth()->user()->companyEmployee->id)->paginate(10);
        } else {
            $practices = Practice::paginate(10);
        }

        return response([
            'items' => $practices->items(),
            'prev_page_url' =>$practices->previousPageUrl(),
            'next_page_url' => $practices->nextPageUrl(),
            'last_page' =>$practices->lastPage(),
            'total' => $practices->total()
        ]);
    }

    public function show(Practice $practice)
    {
        if(auth()->user()->role->role === "Študent" || auth()->user()->role->role === "Zástupca firmy") {
            if($practice->user_id === auth()->id()) {
                return response ($practice->load(["companyEmployee.company","companyEmployee.user","program"]));
            } else if(auth()->user()->companyEmployee->id === $practice->companyEmployee->id) {
                return response ($practice->load(["companyEmployee.company","companyEmployee.user","program"]));
            } else {
                return response("Forbidden", 403);
            }
        }
        
        return response()->json($practice->load(["companyEmployee.company","companyEmployee.user"]));
    }

    public function update(Practice $practice, Request $request)
    {
        if(auth()->user()->role->role === "Študent") {
            if($practice->user_id !== auth()->id()) {
                return response ("Forbidden", 403);
            } 
        }

        $practice->fill($request->all());
        $practice->save();

        return response()->json($practice);
    }

    public function destroy(Practice $practice)
    {
        if(auth()->user()->role->role === "Študent") {
            if($practice->user_id !== auth()->id()) {
                return response ("Forbidden", 403);
            } 
        }

        $practice->delete();

        return response()->json([
            'message' => 'Practice deleted successfully.',
        ]);
    }


}
