<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * Further phases will inject DashboardSummaryService here.
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }
}
