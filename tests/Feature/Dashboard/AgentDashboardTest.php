<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use App\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_agent_can_view_agent_dashboard()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);

        $response = $this->actingAs($agent)->get(route('agent.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('summary');
        $response->assertSee('My Dashboard');
    }

    /** @test */
    public function test_agent_sees_only_assigned_fields()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);
        $assignedField = Field::factory()->create(['assigned_agent_id' => $agent->id, 'name' => 'Assigned Field']);
        $otherField = Field::factory()->create(['name' => 'Other Field']);

        $response = $this->actingAs($agent)->get(route('agent.dashboard'));

        $response->assertSee('Assigned Field');
        $response->assertDontSee('Other Field');
    }
}
