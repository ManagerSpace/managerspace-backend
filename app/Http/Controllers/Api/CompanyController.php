<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            $companies = Company::where('user_id', $user->id)->get();
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return CompanyResource::collection($companies);
    }

    public function store(StoreCompanyRequest $request)
    {
        $validatedData = $request->validated();
        $company = Company::create($validatedData);
        return new CompanyResource($company);
    }

    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $validatedData = $request->validated();
        $company->update($validatedData);
        return new CompanyResource($company);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json(null, 204);
    }
}
