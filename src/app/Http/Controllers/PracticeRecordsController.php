<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\PracticeRecord;
use Illuminate\Http\Request;

class PracticeRecordsController extends Controller
{

    public function store(Request $request, Practice $practice)
    {
        $validatedData = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date',
            'description' => 'required|string',
            'hours' => 'required|integer',
        ]);

        $newRecord = new PracticeRecord();
        $newRecord->from = $validatedData['from'];
        $newRecord->to = $validatedData['to'];
        $newRecord->description = $validatedData['description'];
        $newRecord->hours = $validatedData['hours'];
        $newRecord->practice_id = $practice->id;

        $newRecord->save();

        return response()->json([
            "saved record_id" => $newRecord->id
        ]);
    }

    public function index(Practice $practice){
        if(auth()->user()->role->role === "Študent") {
            if($practice->user_id != auth()->id()) {
                return response ("Forbidden", 403);
            } 
        }

        if(auth()->user()->role->role === "Zástupca firmy") {
            if($practice->companyEmployee->id != auth()->user()->companyEmployee->id) {
                return response ("Forbidden", 403);
            } 
        }

        return response($practice->practiceRecords);
    }

    public function update(PracticeRecord $practiceRecord, Request $request)
    {
        if(auth()->user()->role->role === "Študent") {
            if($practiceRecord->practice->user_id != auth()->id()) {
                return response ("Forbidden", 403);
            } 
        }
        $validatedData = $request->validate([
            'from' => 'date',
            'to' => 'date',
            'description' => 'string',
            'hours' => 'integer',
        ]);

        $practiceRecord->fill($request->all());
        $practiceRecord->save();

        return response()->json($practiceRecord);
    }

    public function destroy(PracticeRecord $practiceRecord)
    {

        if(auth()->user()->role->role === "Študent") {
            if($practiceRecord->practice->user_id != auth()->id()) {
                return response ("Forbidden", 403);
            } 
        }
        
        $practiceRecord->delete();

        return response()->json([
            'message' => 'Practice record deleted successfully.',
        ]);
    }
}
