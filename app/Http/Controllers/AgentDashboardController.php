<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AgentDashboardController extends Controller
{
    /**
     * Display the field agent dashboard.
     *
     * Further phases will scope data to the authenticated agent's fields.
     */
    public function index(): View
    {
        return view('agent.dashboard');
    }
}
