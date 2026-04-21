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
        $user = auth()->user();
        $fields = $user->assignedFields()->with(['creator'])->latest()->get();
        
        $stats = [
            'total_assigned' => $fields->count(),
            'active' => $fields->where('status', 'Active')->count(),
            'at_risk' => $fields->where('status', 'At Risk')->count(),
        ];

        return view('agent.dashboard', compact('fields', 'stats'));
    }
}
