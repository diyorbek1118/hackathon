<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

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
}
