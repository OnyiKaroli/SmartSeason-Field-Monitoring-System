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

        if ($this->isAtRisk($field)) {
            $lastUpdate = $field->updates()->first();
            $daysSinceUpdate = $lastUpdate 
                ? (int) $lastUpdate->created_at->diffInDays(now()) 
                : (int) $field->created_at->diffInDays(now());
            
            return "No updates received in {$daysSinceUpdate} days.";
        }

        return 'Field is progressing normally.';
    }

    private function isAtRisk(Field $field): bool
    {
        // Stale if no update in 7 days
        $lastUpdate = $field->updates()->first();
        $referenceDate = $lastUpdate ? $lastUpdate->created_at : $field->created_at;

        return $referenceDate->lt(now()->subDays(7));
    }
}
