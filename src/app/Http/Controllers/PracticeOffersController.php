<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyDepartment;
use App\Models\Department;
use App\Models\PracticeOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function show(PracticeOffer $practiceOffer)
    {
        return response()->json($practiceOffer->load(["companyDepartment.company","companyDepartment.department"]));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'company_department_id' => 'required|exists:company_department,id'
        ]);
        $practiceOffer = PracticeOffer::create($validatedData);

        return response()->json($practiceOffer);
    }
    public function showByDepartment(Department $department)
    {
        $practiceOffers=Department::find($department->id)->practiceOffers()->paginate(10) ;
        return response($practiceOffers);
        }

    public function showByCompany(Company $company)
    {
        $practiceOffers=Company::find($company->id)->practiceOffers()->paginate(10) ;
        return response($practiceOffers);
    }

    public function update(PracticeOffer $practiceOffer,Request $request)
    {
        if (auth()->user()->role->role === "Zástupca firmy") {
            if (auth()->user()->companyEmployee->company->id !== $practiceOffer->companyDepartment->company_id) {
                return response("Forbidden", 403);
            }
        }
        $validatedData = $request->validate([
            'description' => 'string',
            'phone' => 'string',
            'email' => 'email',
            'company_department_id' => 'exists:company_department,id'
        ]);

        $practiceOffer->fill($validatedData);
        $practiceOffer->save();

        return response()->json($practiceOffer);
    }

    public function destroy( PracticeOffer $practiceOffer,Request $request)
    {

        if (auth()->user()->role->role === "Zástupca firmy") {
            if (auth()->user()->companyEmployee->company->id !== $practiceOffer->companyDepartment->company_id) {
                return response("Forbidden", 403);
            }
        }
        $practiceOffer->delete();
        return response()->json(['message' => 'Practice offer deleted succesfully']);
    }
    public function restore(PracticeOffer $practiceOffer){
        $practiceOffer->restore();
        return response()->json(['message' => 'Úspešne reaktovovaný']);
    }

    public function forceDelete(PracticeOffer $practiceOffer)
    {
        $practiceOffer->forceDelete();
        return response()->json([
            'message' => 'Používateľ bol úspešne odstránený.',
        ]);
    }
    public function indexDeleted()
    {
        $practiceOffers = PracticeOffer::onlyTrashed()->paginate(20);

        return response([
            'items' => $practiceOffers->items(),
            'prev_page_url' => $practiceOffers->previousPageUrl(),
            'next_page_url' => $practiceOffers->nextPageUrl(),
            'last_page' => $practiceOffers->lastPage(),
            'total' => $practiceOffers->total()
        ]);
    }


}
