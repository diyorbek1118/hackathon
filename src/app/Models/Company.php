<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'industry',
        'monthly_avg_income',
        'monthly_avg_expense',
        'currency',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function forecasts()
    {
        return $this->hasMany(Forecast::class);
    }

    public function stressTests()
    {
        return $this->hasMany(StressTest::class);
    }

    public function recommendations()
    {
        return $this->hasMany(AiRecommendation::class);
    }
}
