<?php

namespace App\Http\Controllers\Web;

use App\Http\BaseController;
use App\Services\CompanyService;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CompanyController extends BaseController
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Display a listing of companies
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $companies = $this->companyService->getAllCompanies(true, 15);
        
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company
     * 
     * @return View
     */
    public function create(): View
    {
        return view('companies.create');
    }

    /**
     * Store a newly created company
     * 
     * @param StoreCompanyRequest $request
     * @return RedirectResponse
     */
    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        try {
            $this->companyService->createCompany($request->validated());
            
            return redirect()
                ->route('companies.index')
                ->with('success', 'Kompaniya muvaffaqiyatli yaratildi');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified company
     * 
     * @param int $id
     * @return View|RedirectResponse
     */
    public function show(int $id): View|RedirectResponse
    {
        try {
            $company = $this->companyService->getCompanyById($id);
            
            if (!$company) {
                return redirect()
                    ->route('companies.index')
                    ->with('error', 'Kompaniya topilmadi');
            }
            
            $netProfit = $this->companyService->calculateNetProfit($id);
            
            return view('companies.show', compact('company', 'netProfit'));
        } catch (\Exception $e) {
            return redirect()
                ->route('companies.index')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified company
     * 
     * @param int $id
     * @return View|RedirectResponse
     */
    public function edit(int $id): View|RedirectResponse
    {
        try {
            $company = $this->companyService->getCompanyById($id);
            
            if (!$company) {
                return redirect()
                    ->route('companies.index')
                    ->with('error', 'Kompaniya topilmadi');
            }
            
            return view('companies.edit', compact('company'));
        } catch (\Exception $e) {
            return redirect()
                ->route('companies.index')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified company
     * 
     * @param UpdateCompanyRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateCompanyRequest $request, int $id): RedirectResponse
    {
        try {
            $company = $this->companyService->updateCompany($id, $request->validated());
            
            if (!$company) {
                return redirect()
                    ->route('companies.index')
                    ->with('error', 'Kompaniya topilmadi');
            }
            
            return redirect()
                ->route('companies.show', $id)
                ->with('success', 'Kompaniya muvaffaqiyatli yangilandi');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified company
     * 
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $deleted = $this->companyService->deleteCompany($id);
            
            if (!$deleted) {
                return redirect()
                    ->route('companies.index')
                    ->with('error', 'Kompaniya topilmadi');
            }
            
            return redirect()
                ->route('companies.index')
                ->with('success', 'Kompaniya muvaffaqiyatli o\'chirildi');
        } catch (\Exception $e) {
            return redirect()
                ->route('companies.index')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Display financial status dashboard
     * 
     * @return View
     */
    public function financialStatus(): View
    {
        try {
            $companies = $this->companyService->getCompaniesWithFinancialStatus();
            
            return view('companies.financial-status', compact('companies'));
        } catch (\Exception $e) {
            return view('companies.financial-status')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Search companies
     * 
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function search(Request $request): View|RedirectResponse
    {
        $searchTerm = $request->input('search', '');
        
        if (empty($searchTerm)) {
            return redirect()->route('companies.index');
        }
        
        try {
            $companies = $this->companyService->searchCompanies($searchTerm);
            
            return view('companies.search', compact('companies', 'searchTerm'));
        } catch (\Exception $e) {
            return view('companies.search')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Display companies by industry
     * 
     * @param string $industry
     * @return View
     */
    public function byIndustry(string $industry): View
    {
        try {
            $companies = $this->companyService->getCompaniesByIndustry($industry);
            
            return view('companies.by-industry', compact('companies', 'industry'));
        } catch (\Exception $e) {
            return view('companies.by-industry')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }
}