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

        return response()->json($departments);
    }
    public function update(Request $request, CompanyDepartment $department)
    {
        $department->fill($request->all());
        $department->save();

        return response()->json($department);
    }

    public function show(CompanyDepartment $companyDepartment)
    {

        return response()->json($companyDepartment);
    }

    public function destroy(CompanyDepartment $companyDepartment)
    {
        $companyDepartment->delete();

        return response()->json(['success' => true]);
    }
    public function showByDepartment(Department $department)
    {
        $departments = CompanyDepartment::where("department_id", $department->id)->paginate(10);

        return response()->json($departments);
    }
    public function showByCompany(Company $company)
    {
        $departments = CompanyDepartment::where('company_id', $company->id)->paginate(10);

        return response()->json($departments);
    }
}
