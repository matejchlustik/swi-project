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
