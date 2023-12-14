<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::paginate(20);

        return response([
            'items' => $companies->items(),
            'prev_page_url' =>$companies->previousPageUrl(),
            'next_page_url' => $companies->nextPageUrl(),
            'last_page' =>$companies->lastPage(),
            'total' => $companies->total()
        ]);
    }

    public function update(Company $company, Request $request)
    {
        if (auth()->user()->role->role === "Zástupca firmy") {
            if (auth()->user()->companyEmployee->company->id !== $company->id) {
                return response("Forbidden", 403);
            }
        }
        $validatedData = $request->validate([
            'ICO' => 'string',
            'name' => 'string',
            'city' => 'string',
            'zip_code' => 'string',
            'phone' => 'string',
            'email' => 'string',
            'street' => 'string',
            'house_number' => 'string',
        ]);
        $company->fill($request->all());
        $company->save();

        return response()->json($company);
    }


    public function show(Company $company)
    {
        return response()->json($company);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ICO' => 'required|string',
            'name' => 'required|string',
            'city' => 'required|string',
            'zip_code' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            'street' => 'required|string',
            'house_number' => 'required|string',
        ]);
        $company = Company::create($request->all());

        return response()->json($company,201);
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully.',
        ]);
    }

}
