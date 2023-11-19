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

    public function show(PracticeRecord $practiceRecord)
    {
        //zamestnanec vidiet svojej firmy
        //student vidiet svoje
        //ostatni vidiet vsetko
        $Role = auth()->user()->role;

        if($Role->role == "Admin"){
            return response()->json($practiceRecord);
        }
        if($Role->role == "Vedúci pracoviska"){
            return response()->json($practiceRecord);
        }
        if($Role->role == "Poverený pracovník pracoviska"){
            return response()->json($practiceRecord);
        }
        if($Role->role == "Zástupca firmy"){
            return response()->json($practiceRecord);
        }
        if($Role->role == "Študent"){
            //$practice = Practice::find($practiceRecord->practice_id)->where('user_id', auth()->id())->get();
            //$practiceRecord = PracticeRecord::where('practice_id',$practice->id)->get();
            return response()->json($practiceRecord);
        }


        else return response()->json([
            'message' => 'Missing role error.',
        ]);
    }

    public function index(){
        $practiceRecord = PracticeRecord::paginate(10);

        return response([
            'items' => $practiceRecord->items(),
            'prev_page_url' =>$practiceRecord->previousPageUrl(),
            'next_page_url' => $practiceRecord->nextPageUrl(),
            'last_page' =>$practiceRecord->lastPage(),
            'total' => $practiceRecord->total()
        ]);
    }

    public function update(PracticeRecord $practiceRecord, Request $request)
    {
        $practiceRecord->fill($request->all());
        $practiceRecord->save();

        return response()->json($practiceRecord);
    }

    public function destroy(PracticeRecord $practiceRecord)
    {
        $practiceRecord->delete();

        return response()->json([
            'message' => 'Practice record deleted successfully.',
        ]);
    }
}
