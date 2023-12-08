<?php

namespace App\Http\Controllers;

use App\Models\DepartmentEmployee;
use App\Models\Evaluation;
use App\Models\Practice;
use App\Models\User;
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

                $evaluation = Evaluation::find($newEvaluation->id)->load(["practice","departmentEmployee.user"]);
                return response($evaluation);
            }else
                return response('Evaluation already exists');
    }

    public function index()
    {
        $evaluation = Evaluation::get()->load(["practice","departmentEmployee.user"]);
        return response($evaluation);
    }

    public function update(Practice $practice)
    {

    }

    public function show(Practice $practice)
    {

    }


}
