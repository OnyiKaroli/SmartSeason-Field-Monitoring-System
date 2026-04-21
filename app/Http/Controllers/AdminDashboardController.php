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
        $stats = [
            'total_fields' => \App\Models\Field::count(),
            'completed_fields' => \App\Models\Field::where('current_stage', 'Harvested')->count(),
            'at_risk' => \App\Models\Field::where('current_stage', '!=', 'Harvested')
                ->where('updated_at', '<', now()->subDays(7))
                ->count(),
            'active_fields' => \App\Models\Field::where('current_stage', '!=', 'Harvested')
                ->where('updated_at', '>=', now()->subDays(7))
                ->count(),
        ];

        $recentUpdates = \App\Models\FieldUpdate::with(['field', 'updater'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUpdates'));
    }
}
