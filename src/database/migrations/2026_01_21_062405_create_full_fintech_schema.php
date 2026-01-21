<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. companies
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('industry')->nullable();
            $table->decimal('monthly_avg_income', 15, 2)->nullable();
            $table->decimal('monthly_avg_expense', 15, 2)->nullable();
            $table->string('currency', 10)->default('UZS');
            $table->timestamps();
        });

        // 2. users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['owner','bank','leasing','investor','admin'])->default('owner');
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // 3. accounts
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['cash','bank','wallet']);
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();
        });

        // 4. categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['income','expense']);
        });

        // 5. transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['income','expense']);
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->date('transaction_date');
            $table->timestamps();
        });

        // 6. cashflow_summary
        Schema::create('cashflow_summary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->decimal('total_income', 15, 2)->default(0);
            $table->decimal('total_expense', 15, 2)->default(0);
            $table->decimal('net_cashflow', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();
        });

        // 7. forecasts
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('forecast_start');
            $table->date('forecast_end');
            $table->decimal('predicted_income', 15, 2);
            $table->decimal('predicted_expense', 15, 2);
            $table->decimal('predicted_balance', 15, 2);
            $table->enum('risk_level', ['low','medium','high']);
            $table->timestamps();
        });

        // 8. stress_tests
        Schema::create('stress_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('scenario_name');
            $table->json('parameters')->nullable();
            $table->decimal('result_balance', 15, 2);
            $table->integer('survival_days');
            $table->timestamps();
        });

// 9. ai_recommendations
        Schema::create('ai_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('priority', ['low','medium','high']);
            $table->timestamps();
        });

        // 10. reports
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['monthly','quarterly','custom']);
            $table->date('period_start');
            $table->date('period_end');
            $table->string('file_path');
            $table->timestamps();
        });

        // 11. activity_logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('ai_recommendations');
        Schema::dropIfExists('stress_tests');
        Schema::dropIfExists('forecasts');
        Schema::dropIfExists('cashflow_summary');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('users');
        Schema::dropIfExists('companies');
    }
};