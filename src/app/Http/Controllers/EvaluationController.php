<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\User;

class EvaluationController extends Controller
{

    public function store(Evaluation $evaluation)
    {
        $test = $evaluation->load(["departmentEmployee.company","departmentEmployee.user"]);

        return response($test);
    }

}
