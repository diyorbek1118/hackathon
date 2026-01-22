<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Company;
use App\Models\CashflowSummary;
use Illuminate\Http\Request;
use App\Http\Controllers\AI\AIForecastController;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test the AI Forecast functionality
echo "=== AI Cash Flow Forecast Test ===\n\n";

try {
    // Get a sample company
    $company = Company::first();
    
    if (!$company) {
        echo "No companies found. Please create a company first.\n";
        exit(1);
    }
    
    echo "Company: {$company->name}\n";
    echo "Industry: {$company->industry}\n";
    echo "Currency: {$company->currency}\n\n";

    // Check historical data
    $historicalCount = CashflowSummary::where('company_id', $company->id)->count();
    echo "Historical records found: {$historicalCount}\n\n";

    if ($historicalCount < 3) {
        echo "Warning: Less than 3 historical records available. Forecast accuracy may be limited.\n\n";
    }

    // Show sample historical data
    $sampleData = CashflowSummary::where('company_id', $company->id)
        ->orderBy('date', 'desc')
        ->limit(5)
        ->get();

    echo "Recent Historical Data:\n";
    foreach ($sampleData as $record) {
        echo "  {$record->date}: Income={$record->total_income}, Expense={$record->total_expense}, Net={$record->net_cashflow}\n";
    }
    echo "\n";

    // Create request for AI forecast
    $request = new Request();
    $request->merge([
        'company_id' => $company->id,
        'months_to_predict' => 6
    ]);

    // Initialize controller and generate forecast
    $controller = new AIForecastController();
    echo "Generating AI forecast...\n";
    
    $response = $controller->generateForecast($request);
    $data = $response->getData(true);

    if ($data['success']) {
        echo "✅ Forecast generated successfully!\n\n";
        echo "Generated Forecasts:\n";
        foreach ($data['data'] as $forecast) {
            echo "  Month: {$forecast['forecast_start']}\n";
            echo "    Predicted Income: {$forecast['predicted_income']}\n";
            echo "    Predicted Expense: {$forecast['predicted_expense']}\n";
            echo "    Predicted Balance: {$forecast['predicted_balance']}\n";
            echo "    Risk Level: {$forecast['risk_level']}\n\n";
        }
    } else {
        echo "❌ Forecast generation failed: {$data['message']}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
