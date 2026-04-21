<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Field;
use App\Models\FieldUpdate;
use App\Services\FieldStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class FieldStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    private FieldStatusService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FieldStatusService();
    }

    public function test_harvested_field_returns_completed_status(): void
    {
        $field = Field::factory()->create([
            'current_stage' => 'Harvested'
        ]);

        $this->assertEquals(FieldStatusService::STATUS_COMPLETED, $this->service->getStatus($field));
        $this->assertStringContainsString('harvested', $this->service->getStatusReason($field));
    }

    public function test_recently_created_field_returns_active_status(): void
    {
        $field = Field::factory()->create([
            'current_stage' => 'Planted',
            'created_at' => now()
        ]);

        $this->assertEquals(FieldStatusService::STATUS_ACTIVE, $this->service->getStatus($field));
        $this->assertStringContainsString('progressing normally', $this->service->getStatusReason($field));
    }

    public function test_field_with_recent_update_returns_active_status(): void
    {
        $field = Field::factory()->create([
            'current_stage' => 'Growing',
            'created_at' => now()->subDays(10),
            'last_observation_at' => now()->subDays(2)
        ]);

        $this->assertEquals(FieldStatusService::STATUS_ACTIVE, $this->service->getStatus($field));
    }

    public function test_stale_field_with_no_updates_returns_at_risk_status(): void
    {
        $field = Field::factory()->create([
            'name' => 'Stale Field',
            'current_stage' => 'Planted',
            'created_at' => now()->subDays(8),
            'last_observation_at' => null
        ]);

        $this->assertEquals(FieldStatusService::STATUS_AT_RISK, $this->service->getStatus($field));
        $this->assertStringContainsString('No updates received in 8 days', $this->service->getStatusReason($field));
    }

    public function test_stale_field_with_old_updates_returns_at_risk_status(): void
    {
        $field = Field::factory()->create([
            'current_stage' => 'Growing',
            'created_at' => now()->subDays(20),
            'last_observation_at' => now()->subDays(9)
        ]);

        $this->assertEquals(FieldStatusService::STATUS_AT_RISK, $this->service->getStatus($field));
        $this->assertStringContainsString('No updates received in 9 days', $this->service->getStatusReason($field));
    }

    public function test_needs_attention_logic(): void
    {
        Carbon::setTestNow('2026-04-21 12:00:00');

        // 1. At risk field needs attention (8 days ago)
        $atRiskField = Field::factory()->create([
            'last_observation_at' => now()->subDays(8),
            'current_stage' => 'Growing'
        ]);
        $this->assertTrue($this->service->needsAttention($atRiskField));

        // 2. Field with unchanged stage for 31 days needs attention
        $unchangedField = Field::factory()->create([
            'last_observation_at' => now()->subDays(2), // Status is Active based on update
            'current_stage' => 'Growing'
        ]);
        // Manually set updated_at to something old
        $unchangedField->updated_at = now()->subDays(31);
        $unchangedField->save();
        
        $this->assertTrue($this->service->needsAttention($unchangedField));

        // 3. Harvested field does not need attention
        $harvestedField = Field::factory()->create([
            'current_stage' => 'Harvested',
            'last_observation_at' => now()->subDays(10)
        ]);
        $this->assertFalse($this->service->needsAttention($harvestedField));

        // 4. Normal active field does not need attention
        $activeField = Field::factory()->create([
            'last_observation_at' => now()->subDays(1),
            'current_stage' => 'Growing'
        ]);
        $this->assertFalse($this->service->needsAttention($activeField));

        Carbon::setTestNow(); // Reset
    }
}
