<?php

namespace App\Services;

use App\Models\CashflowSummary;
use App\Models\Company;
use App\Models\Forecast;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ForecastService
{
    private string $aiServerUrl;

    public function __construct()
    {
        $this->aiServerUrl = config('services.ai_server.url');
    }

    public function generateForecast(int $companyId, int $monthsToPredict = 6): array
    {
        $company = Company::find($companyId);
        
        if (!$company) {
            throw new \Exception('Company not found');
        }

        $historicalData = $this->getHistoricalData($companyId);

        if ($historicalData->isEmpty()) {
            throw new \Exception('No historical data found for this company');
        }

        $aiRequest = $this->prepareAIRequest($company, $historicalData, $monthsToPredict);

        $aiResponse = $this->sendToAI($aiRequest);

        if (!$aiResponse['success']) {
            throw new \Exception($aiResponse['message']);
        }

        return $this->parseAndSaveForecasts($companyId, $aiResponse['data']);
    }

    public function getForecasts(int $companyId): \Illuminate\Database\Eloquent\Collection
    {
        return Forecast::where('company_id', $companyId)
            ->with('company')
            ->orderBy('forecast_start', 'asc')
            ->get();
    }

    public function deleteForecasts(int $companyId): int
    {
        return Forecast::where('company_id', $companyId)->delete();
    }

    private function getHistoricalData(int $companyId): \Illuminate\Database\Eloquent\Collection
    {
        return CashflowSummary::where('company_id', $companyId)
            ->orderBy('date', 'asc')
            ->get();
    }

    private function prepareAIRequest(Company $company, $historicalData, int $monthsToPredict): array
    {
        $historicalArray = $historicalData->map(function ($item) {
            return [
                'date' => $item->date->format('Y-m-d'),
                'total_income' => (float) $item->total_income,
                'total_expense' => (float) $item->total_expense,
                'net_cashflow' => (float) $item->net_cashflow,
                'balance' => (float) $item->balance,
            ];
        })->toArray();

        $prompt = $this->generatePrompt($company, $historicalArray, $monthsToPredict);

        return [
            'model' => config('services.ai_server.model'),
            'prompt' => $prompt,
            'stream' => false,
            'options' => config('services.ai_server.options')
        ];
    }

    private function generatePrompt(Company $company, array $historicalData, int $monthsToPredict): string
    {
        $historicalJson = json_encode($historicalData, JSON_PRETTY_PRINT);
        $currentDate = Carbon::now()->format('Y-m-d');

        return <<<PROMPT
You are a financial AI assistant specializing in cash flow forecasting. Analyze historical cash flow data and generate accurate predictions for next {$monthsToPredict} months.

COMPANY INFORMATION:
- Name: {$company->name}
- Industry: {$company->industry}
- Currency: {$company->currency}
- Average Monthly Income: {$company->monthly_avg_income}
- Average Monthly Expense: {$company->monthly_avg_expense}

HISTORICAL CASH FLOW DATA:
{$historicalJson}

TASK:
Generate cash flow predictions for next {$monthsToPredict} months starting from {$currentDate}. Consider:
1. Seasonal patterns in historical data
2. Income and expense trends
3. Net cash flow patterns
4. Industry-specific factors
5. Economic indicators

OUTPUT REQUIREMENTS:
Return ONLY a valid JSON array with the following structure:
[
  {
    "month": "YYYY-MM-01",
    "predicted_income": 123456.78,
    "predicted_expense": 98765.43,
    "predicted_balance": 24691.35,
    "risk_level": "low|medium|high",
    "confidence_score": 0.85,
    "insights": "Brief explanation of the prediction"
  }
]

RISK LEVEL CRITERIA:
- "low": Positive cash flow, stable patterns
- "medium": Moderate cash flow, some volatility
- "high": Negative cash flow or high volatility

IMPORTANT:
- Return ONLY the JSON array, no additional text
- All monetary values should be in {$company->currency}
- Use realistic predictions based on the historical patterns
- Include confidence scores between 0.1 and 1.0
PROMPT;
    }

    private function sendToAI(array $request): array
    {
        try {
            $response = Http::timeout(60)->post($this->aiServerUrl, $request);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'AI server error: ' . $response->status()
                ];
            }

            $data = $response->json();

            if (!isset($data['response'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid AI response format'
                ];
            }

            return [
                'success' => true,
                'data' => $data['response']
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to connect to AI server: ' . $e->getMessage()
            ];
        }
    }

    private function parseAndSaveForecasts(int $companyId, string $aiResponse): array
    {
        try {
            $jsonStart = strpos($aiResponse, '[');
            $jsonEnd = strrpos($aiResponse, ']');
            
            if ($jsonStart === false || $jsonEnd === false) {
                throw new \Exception('No valid JSON found in AI response');
            }

            $jsonString = substr($aiResponse, $jsonStart, $jsonEnd - $jsonStart + 1);
            $predictions = json_decode($jsonString, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON in AI response: ' . json_last_error_msg());
            }

            $savedForecasts = [];

            foreach ($predictions as $prediction) {
                $forecast = Forecast::create([
                    'company_id' => $companyId,
                    'forecast_start' => $prediction['month'],
                    'forecast_end' => $prediction['month'],
                    'predicted_income' => $prediction['predicted_income'],
                    'predicted_expense' => $prediction['predicted_expense'],
                    'predicted_balance' => $prediction['predicted_balance'],
                    'risk_level' => $prediction['risk_level'],
                ]);

                $savedForecasts[] = $forecast->load('company');
            }

            return $savedForecasts;

        } catch (\Exception $e) {
            Log::error('Failed to parse AI response: ' . $e->getMessage());
            throw new \Exception('Failed to parse AI response: ' . $e->getMessage());
        }
    }
}
