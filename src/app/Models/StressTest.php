<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StressTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'scenario_name',
        'parameters',
        'result_balance',
        'survival_days',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
