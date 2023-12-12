<?php

namespace App\Http\Controllers;

use App\Models\DepartmentEmployee;
use App\Models\Evaluation;
use App\Models\Practice;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{

    public function store(Request $request, Practice $practice)
    {
        $validated = $request->validate([
            'evaluation' => 'required|integer|between:0,100',
        ]);

            $DepartmentEmployee = DepartmentEmployee::where('user_id',auth()->id())->first();
            // kontrola ci dana prax s danym zazmestancom este neexistuje
            if (Evaluation::where('practice_id', $practice->id)->where('department_employee_id', $DepartmentEmployee->id)->doesntExist()){
                $newEvaluation = new Evaluation();
                $newEvaluation->practice_id = $practice->id;
                $newEvaluation->department_employee_id = $DepartmentEmployee->id;
                $newEvaluation->evaluation = $validated['evaluation'];
                $newEvaluation->save();

                $evaluation = Evaluation::find($newEvaluation->id);
                return response($evaluation);
            }else
                return response('Evaluation already exists');
    }

    public function index(Practice $practice)
    {
        return response($practice->evaluations->load(["departmentEmployee.user"]));
    }

    public function update(Evaluation $evaluation, Request $request)
    {
        $validated = $request->validate([
            'evaluation' => 'required|integer|between:0,100',
        ]);

        $evaluation->fill($validated);
        $evaluation->save();

        return response()->json($evaluation);
    }

    public function show(Evaluation $evaluation)
    {
        return response()->json($evaluation->load(["practice","departmentEmployee.user"]));
    }

    public function destroy(Evaluation $evaluation)
    {
        if (auth()->user()->role->role !== "Admin") {
            $DepartmentEmployee = DepartmentEmployee::where('user_id',auth()->id())->first();
            if ($DepartmentEmployee->id === $evaluation->department_employee_id) {
                $evaluation->delete();
            }else{
                return response ("Forbidden", 403);
            }
        }else{
            $evaluation->delete();
        }

        return response()->json(['message' => 'Evaluation deleted successfully.']);
    }

}
