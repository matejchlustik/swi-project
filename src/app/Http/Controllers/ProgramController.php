<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::paginate(20);

        return response([
            'items' => $programs->items(),
            'prev_page_url' => $programs->previousPageUrl(),
            'next_page_url' => $programs->nextPageUrl(),
            'last_page' => $programs->lastPage(),
            'total' => $programs->total()
        ]);
    }

    public function show(Program $program)
    {
        return response()->json($program);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'short' => 'required|string',
            'major_id' => 'required|exists:majors,id',
        ]);
        $program = Program::create($validatedData);

        return response()->json($program);
    }

    public function update(Program $program, Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'string',
            'short' => 'string',
            'major_id' => 'exists:majors,id',
        ]);
        $program->fill($validatedData);
        $program->save();

        return response()->json($program);
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return response()->json([
            'message' => 'Program deleted successfully.',
        ]);
    }

    public function restore(Program $program)
    {
        $program->restore();
        return response()->json(['message' => 'úspešne obnovený záznam']);
    }

    public function forceDelete(Program $program)
    {
        $program->forceDelete();
        return response()->json([
            'message' => 'úspešne odstránený záznam',
        ]);
    }

    public function indexDeleted()
    {
        $programs = Program::onlyTrashed()->paginate(20);

        return response([
            'items' => $programs->items(),
            'prev_page_url' => $programs->previousPageUrl(),
            'next_page_url' => $programs->nextPageUrl(),
            'last_page' => $programs->lastPage(),
            'total' => $programs->total()
        ]);
    }
}

