<?php

namespace Tests\Feature\Fields;

use App\Models\Field;
use App\Models\User;
use App\Services\FieldStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldFilteringTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $agent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->agent = User::factory()->create(['role' => 'field_agent']);
    }

    public function test_admin_can_filter_fields_by_crop_type(): void
    {
        Field::factory()->create(['crop_type' => 'Corn', 'name' => 'Corn Field']);
        Field::factory()->create(['crop_type' => 'Wheat', 'name' => 'Wheat Field']);

        $response = $this->actingAs($this->admin)
            ->get(route('fields.index', ['crop_type' => 'Corn']));

        $response->assertStatus(200);
        $response->assertSee('Corn Field');
        $response->assertDontSee('Wheat Field');
    }

    public function test_admin_can_filter_fields_by_stage(): void
    {
        Field::factory()->create(['current_stage' => 'Planted', 'name' => 'Planted Field']);
        Field::factory()->create(['current_stage' => 'Growing', 'name' => 'Growing Field']);

        $response = $this->actingAs($this->admin)
            ->get(route('fields.index', ['stage' => 'Planted']));

        $response->assertStatus(200);
        $response->assertSee('Planted Field');
        $response->assertDontSee('Growing Field');
    }

    public function test_admin_can_filter_fields_by_status_active(): void
    {
        // Active: not harvested and updated recently
        Field::factory()->create([
            'name' => 'Active Field',
            'current_stage' => 'Growing',
            'last_observation_at' => now()->subDays(2)
        ]);

        // At Risk: not harvested and stale
        Field::factory()->create([
            'name' => 'Stale Field',
            'current_stage' => 'Growing',
            'last_observation_at' => now()->subDays(10)
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('fields.index', ['status' => 'Active']));

        $response->assertStatus(200);
        $response->assertSee('Active Field');
        $response->assertDontSee('Stale Field');
    }

    public function test_admin_can_filter_fields_by_status_at_risk(): void
    {
        Field::factory()->create([
            'name' => 'Active Field',
            'current_stage' => 'Growing',
            'last_observation_at' => now()->subDays(2)
        ]);

        Field::factory()->create([
            'name' => 'Stale Field',
            'current_stage' => 'Growing',
            'last_observation_at' => now()->subDays(10)
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('fields.index', ['status' => 'At Risk']));

        $response->assertStatus(200);
        $response->assertSee('Stale Field');
        $response->assertDontSee('Active Field');
    }

    public function test_admin_can_filter_fields_by_agent(): void
    {
        $agent2 = User::factory()->create(['role' => 'field_agent']);
        
        Field::factory()->create(['assigned_agent_id' => $this->agent->id, 'name' => 'Agent 1 Field']);
        Field::factory()->create(['assigned_agent_id' => $agent2->id, 'name' => 'Agent 2 Field']);

        $response = $this->actingAs($this->admin)
            ->get(route('fields.index', ['agent_id' => $this->agent->id]));

        $response->assertStatus(200);
        $response->assertSee('Agent 1 Field');
        $response->assertDontSee('Agent 2 Field');
    }

    public function test_agent_can_filter_own_fields(): void
    {
        Field::factory()->create(['assigned_agent_id' => $this->agent->id, 'crop_type' => 'Corn', 'name' => 'My Corn']);
        Field::factory()->create(['assigned_agent_id' => $this->agent->id, 'crop_type' => 'Wheat', 'name' => 'My Wheat']);
        
        // Another agent's field
        Field::factory()->create(['assigned_agent_id' => $this->admin->id, 'name' => 'Not Mine']);

        $response = $this->actingAs($this->agent)
            ->get(route('fields.index', ['crop_type' => 'Corn']));

        $response->assertStatus(200);
        $response->assertSee('My Corn');
        $response->assertDontSee('My Wheat');
        $response->assertDontSee('Not Mine');
    }
}
