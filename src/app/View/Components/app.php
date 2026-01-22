<?php

namespace App\View\Components;

use App\Models\CashflowSummary;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class app extends Component
{
    public $chartData;
    public function __construct()
    {
        $this->chartData = CashflowSummary::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.app');
    }
}
