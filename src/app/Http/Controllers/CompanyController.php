<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::paginate(20);

       // $next_url = route('api.companies.index', ['page' => $companies->currentPage() + 1]);

        return response([
            'companies' => $companies['data'],
            'first_url' =>$companies['first_page_url'],
            'prev_page' =>$companies['prev_page_url'],
            'last_page' =>$companies['last_page_url'],
            'next_url' => $companies['next_page_url']
        ],200);
    }
    public function update(Company $company, Request $request)
    {
        $company->fill($request->all());
        $company->save();

        return response()->json([
            'message' => 'Company updated successfully.',
        ]);
    }
    public function show(Company $company)
    {
        $company = Company::findOrFail($company);

        return response()->json($company);
    }
    public function store(Company $company)
    {
        $company->save();

        return response()->json([
            'company' => $company,
        ],201);
    }
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully.',
        ]);
    }

}
