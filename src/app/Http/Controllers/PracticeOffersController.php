<?php

namespace App\Http\Controllers;

use App\Models\CompanyDepartment;
use App\Models\PracticeOffers;
use Illuminate\Http\Request;

class PracticeOffersController extends Controller
{
    public function index()
    {
        $practiceOffers = PracticeOffers::paginate(20);

        return response([
            'practiceOffers' => $practiceOffers->items(),
            'prev_page_url' =>$practiceOffers->previousPageUrl(),
            'next_page_url' => $practiceOffers->nextPageUrl(),
            'last_page' =>$practiceOffers->lastPage(),
            'total' => $practiceOffers->total()
        ]);
    }

    public function show(PracticeOffers $practiceOffers)
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
        $practiceOffer = PracticeOffers::create($validatedData);

        return response()->json($practiceOffer);
    }
    public function showByDepartment($departmentId)
    {
        $practiceOffers = PracticeOffers::whereHas('companyDepartment', function ($query) use ($departmentId) {
            $query->where('departments_id', $departmentId);
        })->with('companyDepartment.company', 'companyDepartment.department')->get();

        return view('practice_offers.index', compact('practiceOffers'));
    }

    public function showByCompany($companyId)
    {
        $practiceOffers = PracticeOffers::whereHas('companyDepartment', function ($query) use ($companyId) {
            $query->where('companies_id', $companyId);
        })->with('companyDepartment.company', 'companyDepartment.department')->get();

        return view('practice_offers.index', compact('practiceOffers'));
    }

    public function update(Request $request, $id)
    {
        $user = $request->attributes->get('authenticated_user');
        $practiceOffer = PracticeOffers::findOrFail($id);
        if ($user->companyEmployees->where('company_id', $practiceOffer->company_department->company_id)->isNotEmpty() || $user->role->name !== 'Študent') {
            $validatedData = $request->validate([
                'description' => 'required',
                'phone' => 'required|max:15',
                'email' => 'required|email',
                'company_department_id' => 'required|exists:company_department,id',
            ]);
            $practiceOffer = PracticeOffers::findOrFail($id);
            $practiceOffer->update($validatedData);

            return response()->json($practiceOffer);
        }else {
            return response()->json(['message' => 'Uživateľ nema pravo pristupu.'], 403);
        }

    }

    public function destroy(Request $request,PracticeOffers $practiceOffers)
    {
        $user = $request->attributes->get('authenticated_user');
        $practiceOffer = $practiceOffers->id;
        if ($user->companyEmployees->where('company_id', $practiceOffer->company_department->company_id)->isNotEmpty() || $user->role->name !== 'Študent') {

            $practiceOffers->delete();

            return response()->json(['message' => 'Uspešne zmazaný']);
        }else {
            return response()->json(['message' => 'Uživateľ nema pravo pristupu.'], 403);
        }
    }
}
