<?php

namespace Tests\Feature\Seeding;

use App\Models\Field;
use App\Models\User;
use App\Services\FieldStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoDataTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the database seeding produces the expected demo environment.
     */
    public function test_seeding_produces_expected_users(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', ['email' => 'admin@smartseason.test', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'agent1@smartseason.test', 'role' => 'field_agent']);
        $this->assertDatabaseHas('users', ['email' => 'agent2@smartseason.test', 'role' => 'field_agent']);
    }

    public function test_seeding_produces_variety_of_fields(): void
    {
        $this->seed();

        // Check for specific named fields from FieldSeeder
        $this->assertDatabaseHas('fields', ['name' => 'North Valley Maize']);
        $this->assertDatabaseHas('fields', ['name' => 'East Ridge Wheat']);
        $this->assertDatabaseHas('fields', ['name' => 'South Plateau Soy']);
        $this->assertDatabaseHas('fields', ['name' => 'Western Acres Cotton', 'assigned_agent_id' => null]);

        // Total fields should be at least 10 (5 specific + 5 factory)
        $this->assertGreaterThanOrEqual(10, Field::count());
    }

    public function test_seeding_triggers_at_risk_status(): void
    {
        $this->seed();
        $statusService = app(FieldStatusService::class);

        // East Ridge Wheat was seeded with last_observation_at 10 days ago (Stale)
        $staleField = Field::where('name', 'East Ridge Wheat')->first();
        $status = $statusService->getStatus($staleField);
        $reason = $statusService->getStatusReason($staleField);
        $this->assertEquals('At Risk', $status);
        $this->assertStringContainsString('no updates', strtolower($reason));

        // River Basin Barley was seeded with Growing stage for 60 days (Stuck)
        $stuckField = Field::where('name', 'River Basin Barley')->first();
        $status = $statusService->getStatus($stuckField);
        $this->assertEquals('At Risk', $status);
    }

    public function test_seeding_produces_completed_fields(): void
    {
        $this->seed();
        $statusService = app(FieldStatusService::class);

        $completedField = Field::where('name', 'South Plateau Soy')->first();
        $status = $statusService->getStatus($completedField);
        $this->assertEquals('Completed', $status);
    }

    public function test_dashboards_are_populated_after_seeding(): void
    {
        $this->seed();

        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('North Valley Maize'); // Should show in recent activity or fields needing attention

        $agent1 = User::where('email', 'agent1@smartseason.test')->first();
        $response = $this->actingAs($agent1)->get(route('agent.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('East Ridge Wheat'); // Assigned to agent1
        $response->assertDontSee('South Plateau Soy'); // Assigned to agent2
    }
}
