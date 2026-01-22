<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ForecastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ForecastController extends Controller
{
    private ForecastService $forecastService;

    public function __construct(ForecastService $forecastService)
    {
        $this->forecastService = $forecastService;
    }

    public function generateForecast(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'months_to_predict' => 'integer|min:1|max:12'
        ]);

        try {
            $monthsToPredict = $request->get('months_to_predict', 6);
            $forecasts = $this->forecastService->generateForecast(
                $request->company_id, 
                $monthsToPredict
            );

            return response()->json([
                'success' => true,
                'message' => 'Forecast generated successfully',
                'data' => $forecasts
            ]);

        } catch (\Exception $e) {
            Log::error('AI Forecast generation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate forecast: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getForecasts(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        try {
            $forecasts = $this->forecastService->getForecasts($request->company_id);

            return response()->json([
                'success' => true,
                'data' => $forecasts
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get forecasts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get forecasts: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteForecasts(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        try {
            $deleted = $this->forecastService->deleteForecasts($request->company_id);

            return response()->json([
                'success' => true,
                'message' => "Deleted {$deleted} forecast records"
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete forecasts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete forecasts: ' . $e->getMessage()
            ], 500);
        }
    }
}