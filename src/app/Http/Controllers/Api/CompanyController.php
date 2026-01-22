<?php

namespace App\Http\Controllers\Api;

use App\Http\BaseController;
use App\Services\CompanyService;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanyController extends BaseController
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $paginate = $request->query('paginate', false);
            $perPage = $request->query('per_page', 15);
            
            $companies = $this->companyService->getAllCompanies($paginate, $perPage);
            
            return $this->sendResponse($companies, 'Companies retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving companies', ['error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        try {
            $company = $this->companyService->createCompany($request->validated());
            return $this->sendResponse($company, 'Company created successfully', 201);
        } catch (\Exception $e) {
            return $this->sendError('Error creating company', ['error' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $company = $this->companyService->getCompanyById($id);
            
            if (!$company) {
                return $this->sendError('Company not found', [], 404);
            }
            
            return $this->sendResponse($company, 'Company retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving company', ['error' => $e->getMessage()], 500);
        }
    }
    public function update(UpdateCompanyRequest $request, int $id): JsonResponse
    {
        try {
            $company = $this->companyService->updateCompany($id, $request->validated());
            
            if (!$company) {
                return $this->sendError('Company not found', [], 404);
            }
            
            return $this->sendResponse($company, 'Company updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating company', ['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->companyService->deleteCompany($id);
            
            if (!$deleted) {
                return $this->sendError('Company not found', [], 404);
            }
            
            return $this->sendResponse([], 'Company deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting company', ['error' => $e->getMessage()], 500);
        }
    }
    public function byIndustry(string $industry): JsonResponse
    {
        try {
            $companies = $this->companyService->getCompaniesByIndustry($industry);
            return $this->sendResponse($companies, 'Companies retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving companies', ['error' => $e->getMessage()], 500);
        }
    }

    public function financialStatus(): JsonResponse
    {
        try {
            $companies = $this->companyService->getCompaniesWithFinancialStatus();
            return $this->sendResponse($companies, 'Financial status retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving financial status', ['error' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'search' => 'required|string|min:1',
        ]);

        try {
            $companies = $this->companyService->searchCompanies($request->input('search'));
            return $this->sendResponse($companies, 'Search results retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error searching companies', ['error' => $e->getMessage()], 500);
        }
    }

    public function netProfit(int $id): JsonResponse
    {
        try {
            $netProfit = $this->companyService->calculateNetProfit($id);
            
            if ($netProfit === null) {
                return $this->sendError('Cannot calculate profit - company not found or missing financial data', [], 404);
            }
            
            return $this->sendResponse([
                'company_id' => $id,
                'net_profit' => $netProfit,
            ], 'Net profit calculated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error calculating net profit', ['error' => $e->getMessage()], 500);
        }
    }
}