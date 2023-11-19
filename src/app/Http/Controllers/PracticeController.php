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
        //return response(auth()->user()->role);
        $Practice = Practice::where('user_id', auth()->id())->paginate(10);

        return response([
            'items' => $Practice->items(),
            'prev_page_url' =>$Practice->previousPageUrl(),
            'next_page_url' => $Practice->nextPageUrl(),
            'last_page' =>$Practice->lastPage(),
            'total' => $Practice->total()
        ]);
    }

    public function show(Practice $Practice)
    {
        return response()->json($Practice);
    }

    public function update(Practice $practice, Request $request)
    {
        $practice->fill($request->all());
        $practice->save();

        return response()->json($practice);
    }

    public function destroy(Practice $practice)
    {
        $practice->delete();

        return response()->json([
            'message' => 'Practice deleted successfully.',
        ]);
    }


}
