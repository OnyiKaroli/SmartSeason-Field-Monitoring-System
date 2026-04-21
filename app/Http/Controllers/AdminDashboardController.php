<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(\App\Services\DashboardSummaryService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $summary = $this->dashboardService->getAdminSummary();

        return view('admin.dashboard', compact('summary'));
    }
}
