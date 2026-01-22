<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashflowSummary extends Model
{
    use HasFactory;

    protected $table = 'cashflow_summary';

    protected $fillable = [
        'company_id',
        'date',
        'total_income',
        'total_expense',
        'net_cashflow',
        'balance',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
