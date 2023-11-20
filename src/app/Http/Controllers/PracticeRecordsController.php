<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\PracticeRecord;
use Illuminate\Http\Request;

class PracticeRecordsController extends Controller
{

    public function store(Request $request)
    {
        $newRecord = new PracticeRecord();
        $newRecord->from = $request->input('from');
        $newRecord->to = $request->input('to');
        $newRecord->description = $request->input('description');
        $newRecord->hours = $request->input('hours');
        $newRecord->practice_id = $request->input('practice_id');

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

        $practiceRecords = PracticeRecord::where('practice_id', $practice->id)->paginate(10);

        return response([
            'items' => $practiceRecords->items(),
            'prev_page_url' =>$practiceRecords->previousPageUrl(),
            'next_page_url' => $practiceRecords->nextPageUrl(),
            'last_page' =>$practiceRecords->lastPage(),
            'total' => $practiceRecords->total()
        ]);
    }

    public function update(PracticeRecord $practiceRecord, Request $request)
    {
        if(auth()->user()->role->role === "Študent") {
            if($practiceRecord->practice->user_id != auth()->id()) {
                return response ("Forbidden", 403);
            } 
        }

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
