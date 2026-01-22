<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyService
{
    /**
     * Get all companies with optional pagination
     */
    public function getAllCompanies(bool $paginate = false, int $perPage = 15): Collection|LengthAwarePaginator
    {
        $query = Company::query()->orderBy('created_at', 'desc');
        
        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Get company by ID
     */
    public function getCompanyById(int $id): ?Company
    {
        return Company::find($id);
    }

    /**
     * Create new company
     */
    public function createCompany(array $data): Company
    {
        return Company::create([
            'name' => $data['name'],
            'industry' => $data['industry'] ?? null,
            'monthly_avg_income' => $data['monthly_avg_income'] ?? null,
            'monthly_avg_expense' => $data['monthly_avg_expense'] ?? null,
            'currency' => $data['currency'] ?? 'UZS',
        ]);
    }

    /**
     * Update company
     */
    public function updateCompany(int $id, array $data): ?Company
    {
        $company = $this->getCompanyById($id);
        
        if (!$company) {
            return null;
        }

        $company->update([
            'name' => $data['name'] ?? $company->name,
            'industry' => $data['industry'] ?? $company->industry,
            'monthly_avg_income' => $data['monthly_avg_income'] ?? $company->monthly_avg_income,
            'monthly_avg_expense' => $data['monthly_avg_expense'] ?? $company->monthly_avg_expense,
            'currency' => $data['currency'] ?? $company->currency,
        ]);

        return $company->fresh();
    }

    /**
     * Delete company
     */
    public function deleteCompany(int $id): bool
    {
        $company = $this->getCompanyById($id);
        
        if (!$company) {
            return false;
        }

        return $company->delete();
    }

    /**
     * Get companies by industry
     */
    public function getCompaniesByIndustry(string $industry): Collection
    {
        return Company::where('industry', $industry)->get();
    }

    /**
     * Calculate net profit for a company
     */
    public function calculateNetProfit(int $id): ?float
    {
        $company = $this->getCompanyById($id);
        
        if (!$company || !$company->monthly_avg_income || !$company->monthly_avg_expense) {
            return null;
        }

        return $company->monthly_avg_income - $company->monthly_avg_expense;
    }

    /**
     * Get companies with profit/loss status
     */
    public function getCompaniesWithFinancialStatus(): Collection
    {
        return Company::all()->map(function ($company) {
            $netProfit = $this->calculateNetProfit($company->id);
            
            return [
                'id' => $company->id,
                'name' => $company->name,
                'industry' => $company->industry,
                'monthly_avg_income' => $company->monthly_avg_income,
                'monthly_avg_expense' => $company->monthly_avg_expense,
                'net_profit' => $netProfit,
                'status' => $netProfit > 0 ? 'profitable' : ($netProfit < 0 ? 'loss' : 'break-even'),
                'currency' => $company->currency,
            ];
        });
    }

    /**
     * Search companies by name
     */
    public function searchCompanies(string $searchTerm): Collection
    {
        return Company::where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('industry', 'like', '%' . $searchTerm . '%')
            ->get();
    }
}