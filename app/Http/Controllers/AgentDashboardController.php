<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AgentDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(\App\Services\DashboardSummaryService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the field agent dashboard.
     */
    public function index(): View
    {
        $summary = $this->dashboardService->getAgentSummary(auth()->user());

        return view('agent.dashboard', compact('summary'));
    }
}
