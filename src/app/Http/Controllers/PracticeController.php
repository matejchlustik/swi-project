<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\PracticeRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PracticeController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|date|after_or_equal:today',
            'to' => 'required|date|after:from',
            'company_employee_id' => 'required|integer|not_in:0',
            'department_employee_id' => 'nullable|integer|not_in:0',
            'program_id' => 'required|integer|not_in:0',
            'contract' => 'nullable|image'
        ]);
        $newPractice = new Practice();
        $newPractice->from = $validated['from'];
        $newPractice->to = $validated['to'];
        $newPractice->company_employee_id = $validated['company_employee_id'];
        $newPractice->department_employee_id = $validated['department_employee_id'];
        $newPractice->program_id = $validated['program_id'];
        $newPractice->user_id = auth()->id();

        if ($request->hasFile('contract')){
            $file = $request->file('contract');
            $filename = uniqid().'_'.$file->getClientOriginalName();
            Storage::putFileAs('contracts',$file,$filename);         //ubuntu cmd: sudo chmod -R 777 storage
            $newPractice->contract = $filename;
        }

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

         $request->validate([
            'from' => 'required|date|after_or_equal:today',
            'to' => 'required|date|after:from',
            'company_employee_id' => 'required|integer|not_in:0',
            'department_employee_id' => 'nullable|integer|not_in:0',
            'program_id' => 'required|integer|not_in:0',
        ]);

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

    public function update_contract(int $id, Request $request)
    {
        $practice = Practice::findOrFail($id);

        if(auth()->user()->role->role === "Študent") {
            if($practice->user_id !== auth()->id()) {
                return response ("Forbidden", 403);
            }
        }
        if ($request->hasFile('contract')){
            $request->validate(['contract' => 'image']);
            $contract_name = $practice->contract;
            Storage::delete('contracts/'.$contract_name);
            $file = $request->file('contract');
            $filename = uniqid().'_'.$file->getClientOriginalName();
            Storage::putFileAs('contracts',$file,$filename);         //ubuntu cmd: sudo chmod -R 777 storage
            $practice->contract = $filename;

            $practice->save();
        }
    }





}
