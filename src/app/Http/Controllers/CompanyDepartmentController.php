<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyDepartment;
use App\Models\Department;
use Illuminate\Http\Request;

class CompanyDepartmentController extends Controller
{
    public function index()
    {
        $departments = CompanyDepartment::paginate(10);

        return response([
            'items' => $departments->items(),
            'prev_page_url' =>$departments->previousPageUrl(),
            'next_page_url' => $departments->nextPageUrl(),
            'last_page' =>$departments->lastPage(),
            'total' => $departments->total()
        ]);
    }

    public function update(Request $request, CompanyDepartment $companyDepartment)
    {
        $validatedData = $request->validate([
            'department_id' => 'exists:departments,id',
            'company_id' => 'exists:companies,id',
        ]);
        $companyDepartment->fill($validatedData);
        $companyDepartment->save();

        return response()->json($companyDepartment);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'company_id' => 'required|exists:companies,id',
        ]);

        $companyDepartment = Companydepartment::create($validatedData);

        return response()->json($companyDepartment,201);
    }

    public function show(CompanyDepartment $companyDepartment)
    {

        return response()->json($companyDepartment->load(["department","company"]));
    }

    public function destroy(CompanyDepartment $companyDepartment)
    {
        $companyDepartment->delete();

        return response()->json(['success' => true]);
    }

    public function showByDepartment(Department $department)
    {
        $departments = CompanyDepartment::where("department_id", $department->id)->paginate(10);

        return response([
            'items' => $departments->items(),
            'prev_page_url' =>$departments->previousPageUrl(),
            'next_page_url' => $departments->nextPageUrl(),
            'last_page' =>$departments->lastPage(),
            'total' => $departments->total()
        ]);
    }

    public function showByCompany(Company $company)
    {
        $departments = CompanyDepartment::where('company_id', $company->id)->paginate(10);

        return response([
            'items' => $departments->items(),
            'prev_page_url' =>$departments->previousPageUrl(),
            'next_page_url' => $departments->nextPageUrl(),
            'last_page' =>$departments->lastPage(),
            'total' => $departments->total()
        ]);
    }
    public function restore(CompanyDepartment $companyDepartment){
        $companyDepartment->restore();
        return response()->json(['message' => 'Úspešne obnovený záznam']);
    }

    public function forceDelete(CompanyDepartment $companyDepartment)
    {
        $companyDepartment->forceDelete();
        return response()->json([
            'message' => 'úspešne odstránený záznam.',
        ]);
    }
    public function indexDeleted()
    {
        $companyDepartments = CompanyDepartment::onlyTrashed()->paginate(20);

        return response([
            'items' => $companyDepartments->items(),
            'prev_page_url' => $companyDepartments->previousPageUrl(),
            'next_page_url' => $companyDepartments->nextPageUrl(),
            'last_page' => $companyDepartments->lastPage(),
            'total' => $companyDepartments->total()
        ]);
    }
}
