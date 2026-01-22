<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'forecast_start',
        'forecast_end',
        'predicted_income',
        'predicted_expense',
        'predicted_balance',
        'risk_level',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
