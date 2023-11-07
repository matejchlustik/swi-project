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
    public function update(int $id, Request $request)
    {
        $company = Company::findOrFail($id);

        $company->fill($request->all());
        $company->save();

        return response()->json([
            'company' => $company,
        ],200);
    }
    public function show(int $id)
    {
        $company = Company::findOrFail($id);

        return response()->json($company);
    }
    public function store(Request $request)
    {
        $company = new Company();
        $company->fill($request->all());
        $company->save();

        return response()->json([
            'company' => $company,
        ],201);
    }
    public function destroy(int $id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully.',
        ]);
    }

}
