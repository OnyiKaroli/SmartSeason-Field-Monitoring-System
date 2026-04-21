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
            'created_at' => now()->subDays(10)
        ]);

        FieldUpdate::factory()->create([
            'field_id' => $field->id,
            'created_at' => now()->subDays(2)
        ]);

        // Refresh to load updates relationship
        $field->load('updates');

        $this->assertEquals(FieldStatusService::STATUS_ACTIVE, $this->service->getStatus($field));
    }

    public function test_stale_field_with_no_updates_returns_at_risk_status(): void
    {
        $field = Field::factory()->create([
            'current_stage' => 'Planted',
            'created_at' => now()->subDays(8)
        ]);

        $this->assertEquals(FieldStatusService::STATUS_AT_RISK, $this->service->getStatus($field));
        $this->assertStringContainsString('No updates received in 8 days', $this->service->getStatusReason($field));
    }

    public function test_stale_field_with_old_updates_returns_at_risk_status(): void
    {
        $field = Field::factory()->create([
            'current_stage' => 'Growing',
            'created_at' => now()->subDays(20)
        ]);

        FieldUpdate::factory()->create([
            'field_id' => $field->id,
            'created_at' => now()->subDays(9)
        ]);

        $field->load('updates');

        $this->assertEquals(FieldStatusService::STATUS_AT_RISK, $this->service->getStatus($field));
        $this->assertStringContainsString('No updates received in 9 days', $this->service->getStatusReason($field));
    }
}
