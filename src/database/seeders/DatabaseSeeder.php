<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kompaniyalar yaratish
        $companies = [
            [
                'name' => 'UzTechSolutions',
                'industry' => 'IT Services',
                'monthly_avg_income' => 85000000,
                'monthly_avg_expense' => 62000000,
                'currency' => 'UZS',
            ],
            [
                'name' => 'Toshkent Savdo Markazi',
                'industry' => 'Retail',
                'monthly_avg_income' => 320000000,
                'monthly_avg_expense' => 245000000,
                'currency' => 'UZS',
            ],
            [
                'name' => 'Silk Road Logistics',
                'industry' => 'Transportation',
                'monthly_avg_income' => 156000000,
                'monthly_avg_expense' => 128000000,
                'currency' => 'UZS',
            ],
            [
                'name' => 'Green Farm Export',
                'industry' => 'Agriculture',
                'monthly_avg_income' => 195000000,
                'monthly_avg_expense' => 142000000,
                'currency' => 'UZS',
            ],
            [
                'name' => 'Oila Klinikasi',
                'industry' => 'Healthcare',
                'monthly_avg_income' => 78000000,
                'monthly_avg_expense' => 58000000,
                'currency' => 'UZS',
            ],
        ];

        foreach ($companies as $company) {
            DB::table('companies')->insert(array_merge($company, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 2. Foydalanuvchilar yaratish
        $users = [
            ['name' => 'Aziz Rahimov', 'email' => 'aziz.rahimov@uztech.uz', 'company_id' => 1, 'one_id' => 1001],
            ['name' => 'Madina Karimova', 'email' => 'madina.k@uztech.uz', 'company_id' => 1, 'one_id' => 1002],
            ['name' => 'Jahongir Mamadaliev', 'email' => 'jahongir@savdo.uz', 'company_id' => 2, 'one_id' => 2001],
            ['name' => 'Dildora Usmonova', 'email' => 'dildora@savdo.uz', 'company_id' => 2, 'one_id' => 2002],
            ['name' => 'Rustam Tursunov', 'email' => 'rustam@logistics.uz', 'company_id' => 3, 'one_id' => 3001],
            ['name' => 'Shohruh Alimov', 'email' => 'shohruh@greenfarm.uz', 'company_id' => 4, 'one_id' => 4001],
            ['name' => 'Dr. Nilufar Saidova', 'email' => 'nilufar@klinika.uz', 'company_id' => 5, 'one_id' => 5001],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password123'),
                'one_id' => $user['one_id'],
                'company_id' => $user['company_id'],
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Tranzaksiyalar (so'ngi 6 oy uchun)
        $this->seedTransactions();

        // 4. Cashflow summary
        $this->seedCashflowSummary();

        // 5. Prognozlar
        $this->seedForecasts();

        // 6. Stress testlar
        $this->seedStressTests();

        // 7. AI tavsiyalar
        $this->seedAiRecommendations();

        // 8. Hisobotlar
        $this->seedReports();

        // 9. Faoliyat loglari
        $this->seedActivityLogs();
    }

    private function seedTransactions(): void
    {
        $transactionTypes = [
            1 => [ // UzTechSolutions
                'income' => [
                    ['desc' => 'Website development loyihasi', 'min' => 8000000, 'max' => 15000000],
                    ['desc' => 'Mobile app yaratish', 'min' => 12000000, 'max' => 25000000],
                    ['desc' => 'IT consulting xizmati', 'min' => 5000000, 'max' => 10000000],
                    ['desc' => 'Technical support', 'min' => 3000000, 'max' => 7000000],
                ],
                'expense' => [
                    ['desc' => 'Xodimlar oylik maoshi', 'min' => 35000000, 'max' => 40000000],
                    ['desc' => 'Ofis ijarasi', 'min' => 8000000, 'max' => 8000000],
                    ['desc' => 'Server va hosting xarajatlari', 'min' => 2500000, 'max' => 4000000],
                    ['desc' => 'Marketing va reklama', 'min' => 3000000, 'max' => 6000000],
                    ['desc' => 'Dasturiy ta\'minot litsenziyalari', 'min' => 4000000, 'max' => 8000000],
                ],
            ],
            2 => [ // Toshkent Savdo Markazi
                'income' => [
                    ['desc' => 'Mahsulot sotuvlari', 'min' => 45000000, 'max' => 80000000],
                    ['desc' => 'Optom savdo', 'min' => 30000000, 'max' => 60000000],
                    ['desc' => 'Online buyurtmalar', 'min' => 15000000, 'max' => 35000000],
                ],
                'expense' => [
                    ['desc' => 'Tovar xaridlari', 'min' => 120000000, 'max' => 150000000],
                    ['desc' => 'Xodimlar maoshi', 'min' => 45000000, 'max' => 50000000],
                    ['desc' => 'Do\'kon ijarasi', 'min' => 18000000, 'max' => 18000000],
                    ['desc' => 'Kommunal to\'lovlar', 'min' => 5000000, 'max' => 7000000],
                    ['desc' => 'Transport xarajatlari', 'min' => 8000000, 'max' => 12000000],
                ],
            ],
            3 => [ // Silk Road Logistics
                'income' => [
                    ['desc' => 'Yuk tashish xizmati', 'min' => 25000000, 'max' => 45000000],
                    ['desc' => 'Omborxona xizmatlari', 'min' => 12000000, 'max' => 20000000],
                    ['desc' => 'Express yetkazib berish', 'min' => 8000000, 'max' => 15000000],
                ],
                'expense' => [
                    ['desc' => 'Yoqilg\'i xarajatlari', 'min' => 35000000, 'max' => 45000000],
                    ['desc' => 'Haydovchilar maoshi', 'min' => 28000000, 'max' => 32000000],
                    ['desc' => 'Transport ta\'mirlash', 'min' => 8000000, 'max' => 15000000],
                    ['desc' => 'Sug\'urta to\'lovlari', 'min' => 6000000, 'max' => 8000000],
                    ['desc' => 'Omborxona ijarasi', 'min' => 12000000, 'max' => 12000000],
                ],
            ],
        ];

        for ($month = 5; $month >= 0; $month--) {
            $date = Carbon::now()->subMonths($month);

            foreach ([1, 2, 3] as $companyId) {
                // Daromadlar
                foreach ($transactionTypes[$companyId]['income'] as $incomeType) {
                    $count = rand(3, 8);
                    for ($i = 0; $i < $count; $i++) {
                        DB::table('transactions')->insert([
                            'company_id' => $companyId,
                            'type' => 'income',
                            'amount' => rand($incomeType['min'], $incomeType['max']),
                            'description' => $incomeType['desc'],
                            'transaction_date' => $date->copy()->addDays(rand(1, 28)),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Xarajatlar
                foreach ($transactionTypes[$companyId]['expense'] as $expenseType) {
                    $count = rand(2, 6);
                    for ($i = 0; $i < $count; $i++) {
                        DB::table('transactions')->insert([
                            'company_id' => $companyId,
                            'type' => 'expense',
                            'amount' => rand($expenseType['min'], $expenseType['max']),
                            'description' => $expenseType['desc'],
                            'transaction_date' => $date->copy()->addDays(rand(1, 28)),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    private function seedCashflowSummary(): void
    {
        for ($month = 5; $month >= 0; $month--) {
            $date = Carbon::now()->subMonths($month)->startOfMonth();

            $summaries = [
                ['company_id' => 1, 'income' => 82500000, 'expense' => 61200000],
                ['company_id' => 2, 'income' => 315000000, 'expense' => 242000000],
                ['company_id' => 3, 'income' => 154000000, 'expense' => 126500000],
                ['company_id' => 4, 'income' => 192000000, 'expense' => 139000000],
                ['company_id' => 5, 'income' => 76000000, 'expense' => 56800000],
            ];

            $previousBalance = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

            foreach ($summaries as $summary) {
                $income = $summary['income'] * (1 + (rand(-15, 15) / 100));
                $expense = $summary['expense'] * (1 + (rand(-10, 10) / 100));
                $netCashflow = $income - $expense;
                $balance = $previousBalance[$summary['company_id']] + $netCashflow;
                $previousBalance[$summary['company_id']] = $balance;

                DB::table('cashflow_summary')->insert([
                    'company_id' => $summary['company_id'],
                    'date' => $date,
                    'total_income' => $income,
                    'total_expense' => $expense,
                    'net_cashflow' => $netCashflow,
                    'balance' => $balance,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function seedForecasts(): void
    {
        $forecasts = [
            ['company_id' => 1, 'income' => 88000000, 'expense' => 64000000, 'balance' => 24000000, 'risk' => 'low'],
            ['company_id' => 2, 'income' => 335000000, 'expense' => 258000000, 'balance' => 77000000, 'risk' => 'low'],
            ['company_id' => 3, 'income' => 162000000, 'expense' => 135000000, 'balance' => 27000000, 'risk' => 'medium'],
            ['company_id' => 4, 'income' => 198000000, 'expense' => 145000000, 'balance' => 53000000, 'risk' => 'medium'],
            ['company_id' => 5, 'income' => 79000000, 'expense' => 60000000, 'balance' => 19000000, 'risk' => 'low'],
        ];

        foreach ($forecasts as $forecast) {
            DB::table('forecasts')->insert([
                'company_id' => $forecast['company_id'],
                'forecast_start' => Carbon::now()->addMonth()->startOfMonth(),
                'forecast_end' => Carbon::now()->addMonths(3)->endOfMonth(),
                'predicted_income' => $forecast['income'],
                'predicted_expense' => $forecast['expense'],
                'predicted_balance' => $forecast['balance'],
                'risk_level' => $forecast['risk'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedStressTests(): void
    {
        $scenarios = [
            [
                'company_id' => 1,
                'scenario_name' => 'Daromad 30% kamayishi',
                'parameters' => json_encode(['income_decrease' => 30, 'expense_stable' => true]),
                'result_balance' => 5800000,
                'survival_days' => 87,
            ],
            [
                'company_id' => 1,
                'scenario_name' => 'Xarajatlar 20% oshishi',
                'parameters' => json_encode(['expense_increase' => 20, 'income_stable' => true]),
                'result_balance' => 9500000,
                'survival_days' => 124,
            ],
            [
                'company_id' => 2,
                'scenario_name' => 'Iqtisodiy inqiroz (daromad -40%)',
                'parameters' => json_encode(['income_decrease' => 40, 'expense_decrease' => 15]),
                'result_balance' => -12000000,
                'survival_days' => 58,
            ],
            [
                'company_id' => 3,
                'scenario_name' => 'Yoqilg\'i narxi 50% oshishi',
                'parameters' => json_encode(['fuel_cost_increase' => 50]),
                'result_balance' => 3200000,
                'survival_days' => 72,
            ],
        ];

        foreach ($scenarios as $scenario) {
            DB::table('stress_tests')->insert(array_merge($scenario, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    private function seedAiRecommendations(): void
    {
        $recommendations = [
            [
                'company_id' => 1,
                'title' => 'Marketing xarajatlarini optimallashtirish',
                'description' => 'Marketing xarajatlaringiz oyiga o\'rtacha 4.5 million so\'m. Digital marketing kanallarini qayta ko\'rib chiqish orqali 25-30% tejash mumkin. ROI tahlili Google Analytics va conversion tracking orqali amalga oshirilsin.',
                'priority' => 'high',
            ],
            [
                'company_id' => 1,
                'title' => 'Yangi xizmat yo\'nalishini qo\'shish',
                'description' => 'Cloud solutions va DevOps consulting yo\'nalishlarida bozorda yuqori talab mavjud. Ushbu yo\'nalishni qo\'shish oylik daromadni 15-20 million so\'mga oshirishi mumkin.',
                'priority' => 'medium',
            ],
            [
                'company_id' => 2,
                'title' => 'Tovar aylanmasini tezlashtirish',
                'description' => 'Omborda sekin sotilayotgan tovarlar uchun chegirmali savdo kampaniyalari o\'tkazish tavsiya etiladi. Bu omborxona xarajatlarini kamaytiradi va naqd pul oqimini yaxshilaydi.',
                'priority' => 'high',
            ],
            [
                'company_id' => 2,
                'title' => 'Online savdo platformasini kengaytirish',
                'description' => 'Online buyurtmalar jami daromadning 18%ini tashkil etmoqda. Mobile app va delivery xizmatini yaxshilash orqali bu ko\'rsatkichni 30%gacha oshirish mumkin.',
                'priority' => 'medium',
            ],
            [
                'company_id' => 3,
                'title' => 'Yoqilg\'i iste\'molini kamaytirish',
                'description' => 'Marshrut optimizatsiya dasturidan foydalanish yoqilg\'i xarajatlarini 12-15% kamaytirishi mumkin. GPS tracking va route planning tizimini joriy etish tavsiya etiladi.',
                'priority' => 'high',
            ],
            [
                'company_id' => 3,
                'title' => 'Qo\'shimcha xizmatlar taklif qilish',
                'description' => 'Insurance brokerage va customs clearance xizmatlari sizning mijozlaringiz uchun qo\'shimcha qiymat yaratadi va daromadni 8-10% oshiradi.',
                'priority' => 'low',
            ],
        ];

        foreach ($recommendations as $recommendation) {
            DB::table('ai_recommendations')->insert(array_merge($recommendation, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    private function seedReports(): void
    {
        $reports = [
            [
                'company_id' => 1,
                'type' => 'monthly',
                'period_start' => Carbon::now()->subMonth()->startOfMonth(),
                'period_end' => Carbon::now()->subMonth()->endOfMonth(),
                'file_path' => 'reports/uztech_2024_12_monthly.pdf',
            ],
            [
                'company_id' => 1,
                'type' => 'quarterly',
                'period_start' => Carbon::now()->subMonths(3)->startOfMonth(),
                'period_end' => Carbon::now()->subMonth()->endOfMonth(),
                'file_path' => 'reports/uztech_2024_q4_quarterly.pdf',
            ],
            [
                'company_id' => 2,
                'type' => 'monthly',
                'period_start' => Carbon::now()->subMonth()->startOfMonth(),
                'period_end' => Carbon::now()->subMonth()->endOfMonth(),
                'file_path' => 'reports/savdo_2024_12_monthly.pdf',
            ],
            [
                'company_id' => 3,
                'type' => 'custom',
                'period_start' => Carbon::now()->subMonths(6)->startOfMonth(),
                'period_end' => Carbon::now()->subMonth()->endOfMonth(),
                'file_path' => 'reports/logistics_2024_half_year.pdf',
            ],
        ];

        foreach ($reports as $report) {
            DB::table('reports')->insert(array_merge($report, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    private function seedActivityLogs(): void
    {
        $actions = [
            ['user_id' => 1, 'action' => 'created_transaction', 'meta' => json_encode(['amount' => 15000000, 'type' => 'income'])],
            ['user_id' => 1, 'action' => 'generated_report', 'meta' => json_encode(['type' => 'monthly', 'period' => '2024-12'])],
            ['user_id' => 2, 'action' => 'viewed_forecast', 'meta' => json_encode(['period' => 'Q1-2025'])],
            ['user_id' => 3, 'action' => 'created_transaction', 'meta' => json_encode(['amount' => 55000000, 'type' => 'income'])],
            ['user_id' => 3, 'action' => 'ran_stress_test', 'meta' => json_encode(['scenario' => 'economic_crisis'])],
            ['user_id' => 4, 'action' => 'updated_company_info', 'meta' => json_encode(['field' => 'monthly_avg_income'])],
            ['user_id' => 5, 'action' => 'viewed_cashflow_summary', 'meta' => json_encode(['period' => '6_months'])],
            ['user_id' => 6, 'action' => 'created_transaction', 'meta' => json_encode(['amount' => 32000000, 'type' => 'expense'])],
        ];

        foreach ($actions as $log) {
            DB::table('activity_logs')->insert(array_merge($log, [
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ]));
        }
    }
}
