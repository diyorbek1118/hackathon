<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StressTestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StressTestController extends Controller
{
    private StressTestService $stressTestService;

    public function __construct(StressTestService $stressTestService)
    {
        $this->stressTestService = $stressTestService;
    }

    public function runStressTest(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'scenarios' => 'array',
            'scenarios.*.name' => 'required|string',
            'scenarios.*.description' => 'required|string',
            'scenarios.*.parameters' => 'required|array',
            'scenarios.*.parameters.revenue_change' => 'required|numeric|min:-1|max:1',
            'scenarios.*.parameters.expense_change' => 'required|numeric|min:-1|max:1',
            'scenarios.*.parameters.duration_months' => 'required|integer|min:1|max:12'
        ]);

        try {
            $results = $this->stressTestService->runStressTest(
                $request->company_id,
                $request->get('scenarios', [])
            );

            return response()->json([
                'success' => true,
                'message' => 'Stress test completed successfully',
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Stress test failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to run stress test: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStressTests(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        try {
            $tests = $this->stressTestService->getStressTests($request->company_id);

            return response()->json([
                'success' => true,
                'data' => $tests
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get stress tests: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get stress tests: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteStressTests(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        try {
            $deleted = $this->stressTestService->deleteStressTests($request->company_id);

            return response()->json([
                'success' => true,
                'message' => "Deleted {$deleted} stress test records"
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete stress tests: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete stress tests: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getQuickScenarios()
    {
        return response()->json([
            'success' => true,
            'data' => [
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
                    'name' => 'Market Crash',
                    'description' => '50% revenue drop for 2 months',
                    'parameters' => [
                        'revenue_change' => -0.50,
                        'expense_change' => 0,
                        'duration_months' => 2
                    ]
                ],
                [
                    'name' => 'Inflation Spike',
                    'description' => '25% cost increase for 4 months',
                    'parameters' => [
                        'revenue_change' => 0,
                        'expense_change' => 0.25,
                        'duration_months' => 4
                    ]
                ]
            ]
        ]);
    }
}
