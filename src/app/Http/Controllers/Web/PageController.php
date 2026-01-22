<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\CashflowSummary;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PageController extends Controller
{
    public function main()
    {
        return view('pages.main');
    }

    public function forecast()
    {

        return view('pages.forecast');
    }

    public function statistics()
    {
        return view('pages.statistics');
    }

    public function login()
    {
        return view('pages.login');
    }

    public function chartData(Request $request)
    {
        $companyId = 1;
        $days = $request->input('days', 30); // 30, 60, yoki 90 kun
        
        // O'tgan ma'lumotlarni olish
        $historicalDays = min(15, $days / 2); // Actual data kunlar soni
        $historicalData = $this->getHistoricalData($companyId, $historicalDays);
        
        // Prognoz hisoblash
        $forecastDays = $days - $historicalDays;
        $forecast = $this->calculateForecast($historicalData, $forecastDays);
        
        // JavaScript uchun formatlangan ma'lumotlar
        $formattedData = $this->formatForJavaScript($historicalData, $forecast, $days);
        
        return response()->json($formattedData);
    }
    
    /**
     * O'tgan ma'lumotlarni olish
     */
    private function getHistoricalData($companyId, $days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        // Kunlik cashflow ma'lumotlari
        $dailyData = Transaction::where('company_id', $companyId)
            ->where('transaction_date', '>=', $startDate)
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE -amount END) as net_cashflow')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Agar ma'lumot bo'lmasa, test ma'lumot qaytarish
        if ($dailyData->isEmpty()) {
            return $this->generateTestData($days);
        }
        
        // Balansni hisoblash
        $balance = $this->getStartingBalance($companyId, $startDate);
        $result = [];
        
        foreach ($dailyData as $day) {
            $balance += $day->net_cashflow;
            $result[] = [
                'date' => $day->date,
                'income' => (float) $day->income,
                'expense' => (float) $day->expense,
                'net_cashflow' => (float) $day->net_cashflow,
                'balance' => $balance,
            ];
        }
        
        return $result;
    }
    
    /**
     * Boshlang'ich balansni olish
     */
    private function getStartingBalance($companyId, $startDate)
    {
        $lastSummary = CashflowSummary::where('company_id', $companyId)
            ->where('date', '<', $startDate)
            ->orderBy('date', 'desc')
            ->first();
        
        return $lastSummary ? $lastSummary->balance : 0;
    }
    
    /**
     * Test ma'lumotlar generatsiya qilish
     */
    private function generateTestData($days)
    {
        $result = [];
        $balance = 100000000; // 100M so'm boshlang'ich balans
        
        for ($i = 0; $i < $days; $i++) {
            $income = rand(8000000, 15000000); // 8-15M
            $expense = rand(6000000, 12000000); // 6-12M
            $netCashflow = $income - $expense;
            $balance += $netCashflow;
            
            $result[] = [
                'date' => Carbon::now()->subDays($days - $i)->format('Y-m-d'),
                'income' => $income,
                'expense' => $expense,
                'net_cashflow' => $netCashflow,
                'balance' => $balance,
            ];
        }
        
        return $result;
    }
    
    /**
     * Prognoz hisoblash
     */
    private function calculateForecast($historicalData, $forecastDays)
    {
        if (empty($historicalData)) {
            return $this->getDefaultForecast($forecastDays);
        }
        
        // Statistik ko'rsatkichlar
        $avgIncome = collect($historicalData)->avg('income');
        $avgExpense = collect($historicalData)->avg('expense');
        $avgNetCashflow = $avgIncome - $avgExpense;
        
        // Trend hisoblash
        $trend = $this->calculateTrend($historicalData);
        
        // Volatility (o'zgaruvchanlik)
        $volatility = $this->calculateVolatility($historicalData);
        
        // Mavsumiylik
        $seasonality = $this->calculateSeasonality($historicalData);
        
        // Oxirgi balans
        $lastBalance = end($historicalData)['balance'];
        $lastDate = Carbon::parse(end($historicalData)['date']);
        
        $forecast = [];
        $currentBalance = $lastBalance;
        
        for ($i = 1; $i <= $forecastDays; $i++) {
            $forecastDate = $lastDate->copy()->addDays($i);
            
            // Mavsumiy faktor
            $dayOfWeek = $forecastDate->dayOfWeek;
            $seasonalFactor = $seasonality[$dayOfWeek] ?? 1.0;
            
            // Trend bilan prognoz
            $trendAdjustment = 1 + ($trend['daily_growth'] * $i / 100);
            
            // Tasodifiy o'zgarish (-5% dan +5% gacha)
            $randomFactor = 1 + (rand(-5, 5) / 100);
            
            // Prognoz qiymatlar
            $predictedIncome = $avgIncome * $seasonalFactor * $trendAdjustment * $randomFactor;
            $predictedExpense = $avgExpense * $seasonalFactor * $trendAdjustment * $randomFactor;
            $predictedNetCashflow = $predictedIncome - $predictedExpense;
            
            // Balans
            $currentBalance += $predictedNetCashflow;
            
            // Confidence interval (vaqt o'tishi bilan kamayadi)
            $confidenceBase = 95 - ($i / $forecastDays * 15); // 95% dan 80% gacha
            $confidenceMargin = $volatility * sqrt($i) * 1.5;
            
            $forecast[] = [
                'date' => $forecastDate->format('Y-m-d'),
                'day_number' => $i,
                'predicted_income' => $predictedIncome,
                'predicted_expense' => $predictedExpense,
                'predicted_net_cashflow' => $predictedNetCashflow,
                'predicted_balance' => $currentBalance,
                'upper_bound' => $currentBalance + $confidenceMargin,
                'lower_bound' => $currentBalance - $confidenceMargin,
                'confidence' => $confidenceBase,
            ];
        }
        
        return $forecast;
    }
    
    /**
     * Trend hisoblash
     */
    private function calculateTrend($data)
    {
        $n = count($data);
        if ($n < 2) return ['daily_growth' => 0, 'direction' => 'stable'];
        
        $balances = array_column($data, 'balance');
        
        // O'rtacha kunlik o'sish
        $firstHalf = array_slice($balances, 0, $n / 2);
        $secondHalf = array_slice($balances, $n / 2);
        
        $firstAvg = array_sum($firstHalf) / count($firstHalf);
        $secondAvg = array_sum($secondHalf) / count($secondHalf);
        
        $dailyGrowth = (($secondAvg - $firstAvg) / $firstAvg) * 100 / ($n / 2);
        
        return [
            'daily_growth' => $dailyGrowth,
            'direction' => $dailyGrowth > 0 ? 'increasing' : ($dailyGrowth < 0 ? 'decreasing' : 'stable')
        ];
    }
    
    /**
     * Volatility hisoblash
     */
    private function calculateVolatility($data)
    {
        $balances = array_column($data, 'balance');
        $mean = array_sum($balances) / count($balances);
        
        $squaredDiffs = array_map(function($value) use ($mean) {
            return pow($value - $mean, 2);
        }, $balances);
        
        $variance = array_sum($squaredDiffs) / count($squaredDiffs);
        return sqrt($variance);
    }
    
    /**
     * Mavsumiylik tahlili
     */
    private function calculateSeasonality($data)
    {
        $dayOfWeekTotals = array_fill(0, 7, ['count' => 0, 'sum' => 0]);
        
        foreach ($data as $day) {
            $dayOfWeek = Carbon::parse($day['date'])->dayOfWeek;
            $dayOfWeekTotals[$dayOfWeek]['count']++;
            $dayOfWeekTotals[$dayOfWeek]['sum'] += $day['net_cashflow'];
        }
        
        $avgNetCashflow = collect($data)->avg('net_cashflow');
        if ($avgNetCashflow == 0) $avgNetCashflow = 1;
        
        $seasonality = [];
        for ($i = 0; $i < 7; $i++) {
            if ($dayOfWeekTotals[$i]['count'] > 0) {
                $dayAvg = $dayOfWeekTotals[$i]['sum'] / $dayOfWeekTotals[$i]['count'];
                $seasonality[$i] = $dayAvg / $avgNetCashflow;
            } else {
                $seasonality[$i] = 1.0;
            }
        }
        
        return $seasonality;
    }
    
    /**
     * JavaScript uchun formatlash (frontend kutgan formatda)
     */
    private function formatForJavaScript($historicalData, $forecast, $totalDays)
    {
        $actualLength = count($historicalData);
        
        // Labels (1-kun, 2-kun, ...)
        $labels = array_map(function($i) {
            return ($i + 1) . '-kun';
        }, range(0, $totalDays - 1));
        
        // Actual data (faqat mavjud kunlar, qolganiga null)
        $actualBalances = array_map(function($day) {
            return round($day['balance'] / 1000000, 1); // Million so'mda
        }, $historicalData);
        
        // Predicted data (actual dan keyin)
        $predictedBalances = array_map(function($day) {
            return round($day['predicted_balance'] / 1000000, 1);
        }, $forecast);
        
        // Upper va Lower bounds
        $upperBounds = array_map(function($day) {
            return round($day['upper_bound'] / 1000000, 1);
        }, $forecast);
        
        $lowerBounds = array_map(function($day) {
            return round($day['lower_bound'] / 1000000, 1);
        }, $forecast);
        
        return [
            'labels' => $labels,
            'actual' => $actualBalances,
            'predicted' => $predictedBalances,
            'upperBound' => $upperBounds,
            'lowerBound' => $lowerBounds,
            'raw_data' => [
                'historical' => $historicalData,
                'forecast' => $forecast,
            ]
        ];
    }
    
    /**
     * Default prognoz (ma'lumot bo'lmasa)
     */
    private function getDefaultForecast($days)
    {
        $forecast = [];
        $balance = 100000000; // 100M
        
        for ($i = 1; $i <= $days; $i++) {
            $income = rand(8000000, 15000000);
            $expense = rand(6000000, 12000000);
            $netCashflow = $income - $expense;
            $balance += $netCashflow;
            
            $forecast[] = [
                'date' => Carbon::now()->addDays($i)->format('Y-m-d'),
                'day_number' => $i,
                'predicted_income' => $income,
                'predicted_expense' => $expense,
                'predicted_net_cashflow' => $netCashflow,
                'predicted_balance' => $balance,
                'upper_bound' => $balance + ($balance * 0.1),
                'lower_bound' => $balance - ($balance * 0.1),
                'confidence' => 85,
            ];
        }
        
        return $forecast;
    }
}