<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function main()
    {
        return view('pages.main');
    }
    public function statistics()
    {
        return view('pages.statistics');
    }

    public function forecast()
    {
        return view('pages.forecast');
    }
}
