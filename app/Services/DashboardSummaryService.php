<?php

namespace App\Services;

use App\Models\Field;
use App\Models\FieldUpdate;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardSummaryService
{
    /**
     * Get summary data for the admin dashboard.
     */
    public function getAdminSummary(): array
    {
        $fields = Field::all();
        
        $statusBreakdown = [
            FieldStatusService::STATUS_ACTIVE => $fields->where('status', FieldStatusService::STATUS_ACTIVE)->count(),
            FieldStatusService::STATUS_AT_RISK => $fields->where('status', FieldStatusService::STATUS_AT_RISK)->count(),
            FieldStatusService::STATUS_COMPLETED => $fields->where('status', FieldStatusService::STATUS_COMPLETED)->count(),
        ];

        $stageBreakdown = collect(Field::STAGES)->mapWithKeys(function ($stage) use ($fields) {
            return [$stage => $fields->where('current_stage', $stage)->count()];
        })->toArray();

        $recentUpdates = FieldUpdate::with(['field', 'updater'])
            ->latest()
            ->take(5)
            ->get();

        $needsAttention = $fields->filter(function ($field) {
            return $field->status === FieldStatusService::STATUS_AT_RISK;
        })->take(5);

        $agentActivity = User::where('role', 'field_agent')
            ->withCount('assignedFields')
            ->get()
            ->map(function ($agent) {
                $lastUpdate = FieldUpdate::where('updated_by', $agent->id)
                    ->latest()
                    ->first();

                return [
                    'agent' => $agent,
                    'fields_count' => $agent->assigned_fields_count,
                    'last_update_at' => $lastUpdate ? $lastUpdate->created_at : null,
                ];
            });

        return [
            'total_fields' => $fields->count(),
            'status_breakdown' => $statusBreakdown,
            'stage_breakdown' => $stageBreakdown,
            'recent_updates' => $recentUpdates,
            'needs_attention' => $needsAttention,
            'agent_activity' => $agentActivity,
        ];
    }

    /**
     * Get summary data for a field agent dashboard.
     */
    public function getAgentSummary(User $agent): array
    {
        $fields = $agent->assignedFields()->get();

        $needsUpdates = $fields->filter(function ($field) {
            return $field->status === FieldStatusService::STATUS_AT_RISK;
        });

        $recentUpdates = FieldUpdate::where('updated_by', $agent->id)
            ->with('field')
            ->latest()
            ->take(5)
            ->get();

        return [
            'total_assigned' => $fields->count(),
            'active_count' => $fields->where('status', FieldStatusService::STATUS_ACTIVE)->count(),
            'at_risk_count' => $fields->where('status', FieldStatusService::STATUS_AT_RISK)->count(),
            'needs_updates' => $needsUpdates,
            'recent_updates' => $recentUpdates,
            'fields' => $fields,
        ];
    }
}
