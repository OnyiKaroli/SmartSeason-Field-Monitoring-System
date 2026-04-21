<?php

namespace App\Services;

use App\Models\Field;
use Illuminate\Support\Carbon;

class FieldStatusService
{
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_AT_RISK = 'At Risk';
    public const STATUS_COMPLETED = 'Completed';

    public function getStatus(Field $field): string
    {
        if ($field->current_stage === 'Harvested') {
            return self::STATUS_COMPLETED;
        }

        if ($this->isAtRisk($field)) {
            return self::STATUS_AT_RISK;
        }

        return self::STATUS_ACTIVE;
    }

    public function getStatusReason(Field $field): string
    {
        if ($field->current_stage === 'Harvested') {
            return 'Field has been harvested.';
        }

        $referenceDate = $field->last_observation_at ?? $field->created_at;
        $daysSinceUpdate = (int) $referenceDate->diffInDays(now());

        if ($daysSinceUpdate > 7) {
            return "No updates received in {$daysSinceUpdate} days.";
        }

        $daysSinceStageChange = (int) $field->updated_at->diffInDays(now());
        if ($daysSinceStageChange > 45) {
            return "Stuck in {$field->current_stage} stage for {$daysSinceStageChange} days.";
        }

        return 'Field is progressing normally.';
    }

    public function needsAttention(Field $field): bool
    {
        if ($field->current_stage === 'Harvested') {
            return false;
        }

        // Needs attention if at risk (no update in 7 days)
        if ($this->isAtRisk($field)) {
            return true;
        }

        // Needs attention if stage unchanged for too long (e.g. 30 days)
        if ($field->updated_at->lt(now()->subDays(30))) {
            return true;
        }

        return false;
    }

    private function isAtRisk(Field $field): bool
    {
        // Stale if no update in 7 days
        $referenceDate = $field->last_observation_at ?? $field->created_at;
        if ($referenceDate->lt(now()->subDays(7))) {
            return true;
        }

        // Also at risk if stage unchanged for too long (e.g. 45 days)
        if ($field->updated_at->lt(now()->subDays(45))) {
            return true;
        }

        return false;
    }
}
