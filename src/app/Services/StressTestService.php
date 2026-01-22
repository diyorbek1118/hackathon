<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Forecast;
use App\Models\StressTest;
use Illuminate\Support\Facades\Log;

class StressTestService
{
    public function runStressTest(int $companyId, array $scenarios = []): array
    {
        $company = Company::find($companyId);
        
        if (!$company) {
            throw new \Exception('Company not found');
        }

        $forecasts = Forecast::where('company_id', $companyId)
            ->orderBy('forecast_start', 'asc')
            ->get();

        if ($forecasts->isEmpty()) {
            throw new \Exception('No forecast data found. Please generate forecasts first.');
        }

        $defaultScenarios = $this->getDefaultScenarios();
        $testScenarios = array_merge($defaultScenarios, $scenarios);

        $results = [];

        foreach ($testScenarios as $scenario) {
            $result = $this->runScenario($company, $forecasts, $scenario);
            $results[] = $result;
        }

        $this->saveResults($companyId, $results);

        return $results;
    }

    private function getDefaultScenarios(): array
    {
        return [
            [
                'name' => 'Revenue Drop 30%',
                'description' => '30% decrease in revenue for 3 months',
                'parameters' => [
                    'revenue_change' => -0.30,
                    'expense_change' => 0,
                    'duration_months' => 3
                ]
            ],
            [
                'name' => 'Cost Increase 20%',
                'description' => '20% increase in expenses for 6 months',
                'parameters' => [
                    'revenue_change' => 0,
                    'expense_change' => 0.20,
                    'duration_months' => 6
                ]
            ],
            [
                'name' => 'Combined Crisis',
                'description' => '25% revenue drop + 15% cost increase for 4 months',
                'parameters' => [
                    'revenue_change' => -0.25,
                    'expense_change' => 0.15,
                    'duration_months' => 4
                ]
            ],
            [
                'name' => 'Extended Downtime',
                'description' => '50% revenue reduction for 2 months',
                'parameters' => [
                    'revenue_change' => -0.50,
                    'expense_change' => 0,
                    'duration_months' => 2
                ]
            ]
        ];
    }

    private function runScenario(Company $company, $forecasts, array $scenario): array
    {
        $params = $scenario['parameters'];
        $currentBalance = $company->monthly_avg_income - $company->monthly_avg_expense;
        $worstBalance = $currentBalance;
        $survivalDays = 0;
        $monthlyBurnRate = 0;

        foreach ($forecasts as $forecast) {
            if ($survivalDays >= $params['duration_months'] * 30) {
                break;
            }

            $adjustedIncome = $forecast->predicted_income * (1 + $params['revenue_change']);
            $adjustedExpense = $forecast->predicted_expense * (1 + $params['expense_change']);
            $netCashflow = $adjustedIncome - $adjustedExpense;
            
            $currentBalance += $netCashflow;
            
            if ($currentBalance < $worstBalance) {
                $worstBalance = $currentBalance;
            }

            if ($netCashflow < 0) {
                $monthlyBurnRate = abs($netCashflow);
            }

            $survivalDays++;
        }

        $riskLevel = $this->calculateRiskLevel($worstBalance, $monthlyBurnRate, $currentBalance);

        return [
            'scenario_name' => $scenario['name'],
            'description' => $scenario['description'],
            'parameters' => $params,
            'result_balance' => $currentBalance,
            'worst_balance' => $worstBalance,
            'survival_days' => $survivalDays * 30,
            'monthly_burn_rate' => $monthlyBurnRate,
            'risk_level' => $riskLevel,
            'recommendations' => $this->generateRecommendations($riskLevel, $monthlyBurnRate, $currentBalance)
        ];
    }

    private function calculateRiskLevel(float $worstBalance, float $burnRate, float $finalBalance): string
    {
        if ($worstBalance < 0 || $burnRate > $finalBalance * 0.2) {
            return 'high';
        } elseif ($worstBalance < $finalBalance * 0.3 || $burnRate > $finalBalance * 0.1) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    private function generateRecommendations(string $riskLevel, float $burnRate, float $balance): array
    {
        $recommendations = [];

        if ($riskLevel === 'high') {
            $recommendations[] = 'Immediate action required - secure emergency funding';
            $recommendations[] = 'Reduce non-essential expenses immediately';
            $recommendations[] = 'Consider temporary revenue acceleration measures';
        } elseif ($riskLevel === 'medium') {
            $recommendations[] = 'Monitor cash flow closely';
            $recommendations[] = 'Prepare contingency funding options';
            $recommendations[] = 'Review expense reduction opportunities';
        } else {
            $recommendations[] = 'Maintain current financial practices';
            $recommendations[] = 'Continue regular monitoring';
        }

        if ($burnRate > 0) {
            $recommendations[] = "Monthly burn rate: {$burnRate} - focus on reducing this";
        }

        if ($balance < 0) {
            $recommendations[] = 'Negative balance detected - urgent intervention needed';
        }

        return $recommendations;
    }

    private function saveResults(int $companyId, array $results): void
    {
        foreach ($results as $result) {
            StressTest::create([
                'company_id' => $companyId,
                'scenario_name' => $result['scenario_name'],
                'parameters' => json_encode($result['parameters']),
                'result_balance' => $result['result_balance'],
                'survival_days' => $result['survival_days']
            ]);
        }
    }

    public function getStressTests(int $companyId): \Illuminate\Database\Eloquent\Collection
    {
        return StressTest::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function deleteStressTests(int $companyId): int
    {
        return StressTest::where('company_id', $companyId)->delete();
    }
}
