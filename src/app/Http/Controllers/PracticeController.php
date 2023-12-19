<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Practice;
use Illuminate\Http\Request;
use App\Models\PracticeRecord;
use App\Models\PracticeStatus;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class PracticeController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after:from',
            'company_employee_id' => 'required|integer|not_in:0|exists:company_employees,id',
            'program_id' => 'required|integer|not_in:0|exists:programs,id',
            'contract' => ["nullable",File::types(['docx', 'pdf'])],

        ]);
        $newPractice = new Practice();
        $newPractice->practice_status_id = PracticeStatus::firstWhere("status", "Neschválená")->id;
        $newPractice->from = $validated['from'];
        $newPractice->to = $validated['to'];
        $newPractice->company_employee_id = $validated['company_employee_id'];
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
             'from' => 'date',
             'to' => 'date|after:from',
             'company_employee_id' => 'integer|not_in:0',
             'program_id' => 'integer|not_in:0|exists:programs,id',
             'contract' => ["nullable",File::types(['docx', 'pdf'])],
             'practice_status_id' => 'integer|exists:practice_statuses,id'
         ]);

        $practice->fill($request->only(['from','to','company_employee_id','program_id']));

        $userRole = auth()->user()->role->role;
        if($userRole === "Admin" || $userRole === "Vedúci pracoviska" || $userRole === "Poverený pracovník pracoviska") {
            if($request->has('practice_status_id')) {
                $practice->practice_status_id = $request["practice_status_id"];
            } else {
                return response("Cannot change practice status", 403);
            }
        }

        if ($request->hasFile('contract')){
            Storage::delete('contracts/'.$practice->contract);
            $file = $request->file('contract');
            $filename = uniqid().'_'.$file->getClientOriginalName();
            Storage::putFileAs('contracts',$file,$filename);         //ubuntu cmd: sudo chmod -R 777 storage
            $practice->contract = $filename;
        }

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

    public function download_contract(Practice $practice)
    {
        if(auth()->user()->role->role === "Študent") {
            if($practice->user_id === auth()->id()) {
                if (!empty($practice->contract)) return Storage::download('contracts/'.$practice->contract);
                else return response()->json([
                    'message' => 'Practice does not have contract.']);
            } else return response ("Forbidden", 403);
        }else {
            if (!empty($practice->contract)) return Storage::download('contracts/'.$practice->contract);
            else return response()->json([
                'message' => 'Practice does not have contract.']);
        }
    }

    public function getPracticesByPracticeStatus(PracticeStatus $practiceStatus) {
        $practices = Practice::where('practice_status_id', $practiceStatus->id)->latest()->paginate(10);

        return response([
            'items' => $practices->items(),
            'prev_page_url' =>$practices->previousPageUrl(),
            'next_page_url' => $practices->nextPageUrl(),
            'last_page' =>$practices->lastPage(),
            'total' => $practices->total()
        ]);
    }

    public function getPracticesByProgram(Program $program) {
        $practices = Practice::where('program_id', $program->id)->latest()->paginate(10);

        return response([
            'items' => $practices->items(),
            'prev_page_url' =>$practices->previousPageUrl(),
            'next_page_url' => $practices->nextPageUrl(),
            'last_page' =>$practices->lastPage(),
            'total' => $practices->total()
        ]);
    }

    public function generateCompletionConfirmation(Practice $practice) {

        if(auth()->user()->role->role === "Študent") {
            if($practice->user_id !== auth()->id()) {
                return response ("Forbidden", 403);
            }
        }

        if($practice->completion_confirmation) {
            return Storage::download('completionConfirmations/'.$practice->completion_confirmation);
        }
        $pdf = PDF::loadView('practiceConfirmation',
        [
            'practiceRecords' => $practice->practiceRecords,
            'practice' => $practice,
            'company' => $practice->company,
            'companyEmployee' => $practice->companyEmployee,
            'user' => $practice->user,
            'program' => $practice->program
        ]);
        $content = $pdf->download()->getOriginalContent();
        Storage::put("completionConfirmations/completionConfirmation".$practice->id.".pdf",$content);         //ubuntu cmd: sudo chmod -R 777 storage
        $practice->completion_confirmation = "completionConfirmation".$practice->id.".pdf";

        $practice->save();

        return Storage::download('completionConfirmations/'.$practice->completion_confirmation);
    }

    public function regenerateCompletionConfirmation(Practice $practice) {

        if(auth()->user()->role->role === "Študent") {
            if($practice->user_id !== auth()->id()) {
                return response ("Forbidden", 403);
            }
        }

        $pdf = PDF::loadView('practiceConfirmation',
        [
            'practiceRecords' => $practice->practiceRecords,
            'practice' => $practice,
            'company' => $practice->company,
            'companyEmployee' => $practice->companyEmployee,
            'user' => $practice->user,
            'program' => $practice->program
        ]);
        $content = $pdf->download()->getOriginalContent();
        Storage::put("completionConfirmations/completionConfirmation".$practice->id.".pdf",$content);         //ubuntu cmd: sudo chmod -R 777 storage
        $practice->completion_confirmation = "completionConfirmation".$practice->id.".pdf";

        $practice->save();

        return Storage::download('completionConfirmations/'.$practice->completion_confirmation);
    }
    public function restore(Practice $practice){
        $practice->restore();
        return response()->json(['message' => 'Úspešne obnovený záznam']);
    }

    public function forceDelete(Practice $practice)
    {
        $practice->forceDelete();
        return response()->json([
            'message' => 'úspešne odstránený záznam',
        ]);
    }
    public function indexDeleted()
    {
        $practices = Practice::onlyTrashed()->paginate(20);

        return response([
            'items' => $practices->items(),
            'prev_page_url' => $practices->previousPageUrl(),
            'next_page_url' => $practices->nextPageUrl(),
            'last_page' => $practices->lastPage(),
            'total' => $practices->total()
        ]);
    }


}
