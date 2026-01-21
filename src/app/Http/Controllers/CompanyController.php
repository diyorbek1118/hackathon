<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return Company::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'industry' => 'nullable|string',
            'monthly_avg_income' => 'nullable|numeric',
            'monthly_avg_expense' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
        ]);

        return Company::create($data);
    }

    public function show(Company $company)
    {
        return $company;
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'industry' => 'sometimes|nullable|string',
            'monthly_avg_income' => 'sometimes|nullable|numeric',
            'monthly_avg_expense' => 'sometimes|nullable|numeric',
            'currency' => 'sometimes|nullable|string|max:10',
        ]);

        $company->update($data);
        return $company;
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->noContent();
    }
}
