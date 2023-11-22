<?php

namespace App\Http\Controllers;

use App\Models\CompanyDepartment;
use App\Models\Department;
use App\Models\PracticeOffer;
use Illuminate\Http\Request;

class PracticeOffersController extends Controller
{
    public function index()
    {
        $practiceOffers = PracticeOffer::paginate(20);

        return response([
            'practiceOffers' => $practiceOffers->items(),
            'prev_page_url' =>$practiceOffers->previousPageUrl(),
            'next_page_url' => $practiceOffers->nextPageUrl(),
            'last_page' =>$practiceOffers->lastPage(),
            'total' => $practiceOffers->total()
        ]);
    }

    public function show(PracticeOffer $practiceOffers)
    {
        return response()->json($practiceOffers);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required',
            'phone' => 'required|max:15',
            'email' => 'required|email',
            'company_department_id' => 'required|exists:company_department,id',
        ]);
        $practiceOffer = PracticeOffer::create($validatedData);

        return response()->json($practiceOffer);
    }
    public function showByDepartment($departmentId)
    {
        $practiceOffers = PracticeOffer::whereHas('companyDepartment', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })->get();
            return response()->json([
                'practiceOffers' => $practiceOffers->map(function ($practiceOffer) {
                    return [
                        'id' => $practiceOffer->id,
                        'description' => $practiceOffer->description,
                        'phone' => $practiceOffer->phone,
                        'email' => $practiceOffer->email,
                        'company' => $practiceOffer->companyDepartment->company,
                        'department' => $practiceOffer->companyDepartment->department,
                    ];
                }),
            ]);
       // return response($department->practiceOffers);
        }

    public function showByCompany($companyId)
    {
        $practiceOffers = PracticeOffer::whereHas('companyDepartment', function ($query) use ($companyId) {
            $query->where('companies_id', $companyId);
        })->get();

        return response()->json([
            'practiceOffers' => $practiceOffers->map(function ($practiceOffer) {
                return [
                    'id' => $practiceOffer->id,
                    'description' => $practiceOffer->description,
                    'phone' => $practiceOffer->phone,
                    'email' => $practiceOffer->email,
                    'company' => $practiceOffer->companyDepartment->company,
                    'department' => $practiceOffer->companyDepartment->department,
                ];
            }),
        ]);
    }

    public function update(Request $request, PracticeOffer $practiceOffers)
    {
        $practiceOffer = PracticeOffer::findOrFail($practiceOffers);
        $user = $request->attributes->get('authenticated_user');
        $companyId = Auth::user()->companyEmployees->where('company_id', $practiceOffer->company_department->company_id)->first()->company_id;
        if ($user->role->name === 'Študent'){
            return response ("Forbidden", 403);
        }
        if (!$companyId) {
            return response ("Forbidden", 403);
        }
        else{
            $validatedData = $request->validate([
                'description' => 'required',
                'phone' => 'required|max:30',
                'email' => 'required|email',
                'company_department_id' => 'required|exists:company_department,id',
            ]);

            $practiceOffer->update($validatedData);

            return response()->json($practiceOffer);
        }
    }

    public function destroy(Request $request, PracticeOffer $practiceOffers)
    {
        $practiceOffer = PracticeOffer::findOrFail($practiceOffers);
        $user = $request->attributes->get('authenticated_user');
        $companyId = Auth::user()->companyEmployees->where('company_id', $practiceOffer->company_department->company_id)->first()->company_id;
        if ($user->role->name === 'Študent'){
            return response ("Forbidden", 403);
        }
        if (!$companyId) {
            return response ("Forbidden", 403);
        }
        else{

            return response()->json(['message' => 'Úspěšně smazáno']);
        }
    }

}
