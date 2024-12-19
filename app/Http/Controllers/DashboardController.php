<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function applicant()
    {
        return view('pages.applicant.dashboard.index');
    }

    public function manager()
    {
        return view('pages.manager.dashboard.index');
    }

    public function administrator()
    {
        return view('pages.administrator.dashboard.index');
    }
}
