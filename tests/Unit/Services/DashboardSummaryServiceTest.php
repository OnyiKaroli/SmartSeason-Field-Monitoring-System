<?php

namespace Tests\Unit\Services;

use App\Models\Field;
use App\Models\FieldUpdate;
use App\Models\User;
use App\Services\DashboardSummaryService;
use App\Services\FieldStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardSummaryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardSummaryService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DashboardSummaryService();
    }

    /** @test */
    public function test_it_calculates_correct_admin_summary_stats()
    {
        // Setup fields with different statuses
        // 1 Completed
        Field::factory()->create(['current_stage' => 'Harvested']);
        
        // 1 At Risk (Old update)
        $atRiskField = Field::factory()->create([
            'current_stage' => 'Growing',
            'last_observation_at' => now()->subDays(10)
        ]);
        FieldUpdate::factory()->create([
            'field_id' => $atRiskField->id,
            'created_at' => now()->subDays(10),
            'observed_at' => now()->subDays(10)
        ]);

        // 1 Active (Recent update)
        $activeField = Field::factory()->create([
            'current_stage' => 'Planted',
            'last_observation_at' => now()->subDays(1)
        ]);
        FieldUpdate::factory()->create([
            'field_id' => $activeField->id,
            'created_at' => now()->subDays(1),
            'observed_at' => now()->subDays(1)
        ]);

        $summary = $this->service->getAdminSummary();

        $this->assertEquals(3, $summary['total_fields']);
        $this->assertEquals(1, $summary['status_breakdown'][FieldStatusService::STATUS_ACTIVE]);
        $this->assertEquals(1, $summary['status_breakdown'][FieldStatusService::STATUS_AT_RISK]);
        $this->assertEquals(1, $summary['status_breakdown'][FieldStatusService::STATUS_COMPLETED]);
    }

    /** @test */
    public function test_it_calculates_correct_agent_summary_stats()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);
        
        // Assigned fields
        Field::factory()->create([
            'assigned_agent_id' => $agent->id,
            'current_stage' => 'Planted',
            'last_observation_at' => now()->subDays(1)
        ]);
        
        $atRiskField = Field::factory()->create([
            'assigned_agent_id' => $agent->id,
            'current_stage' => 'Growing',
            'last_observation_at' => now()->subDays(10)
        ]);
        FieldUpdate::factory()->create([
            'field_id' => $atRiskField->id,
            'created_at' => now()->subDays(10),
            'observed_at' => now()->subDays(10)
        ]);

        // Unassigned field (should not be in agent summary)
        Field::factory()->create(['current_stage' => 'Ready']);

        $summary = $this->service->getAgentSummary($agent);

        $this->assertEquals(2, $summary['total_assigned']);
        $this->assertEquals(1, $summary['active_count']);
        $this->assertEquals(1, $summary['at_risk_count']);
        $this->assertCount(1, $summary['needs_updates']);
    }
}
