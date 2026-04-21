<?php

namespace Tests\Feature\Fields;

use App\Models\Field;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_field_to_agent_during_creation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $agent = User::factory()->create(['role' => 'field_agent']);

        $response = $this->actingAs($admin)->post('/fields', [
            'name' => 'Test Field',
            'crop_type' => 'Wheat',
            'planting_date' => now()->subDays(5)->format('Y-m-d'),
            'current_stage' => 'Planted',
            'assigned_agent_id' => $agent->id,
        ]);

        $response->assertRedirect('/fields');
        $this->assertDatabaseHas('fields', [
            'name' => 'Test Field',
            'assigned_agent_id' => $agent->id,
        ]);
    }

    public function test_admin_can_assign_field_to_agent_during_update()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $agent = User::factory()->create(['role' => 'field_agent']);
        $field = Field::factory()->create();

        $response = $this->actingAs($admin)->put('/fields/' . $field->id, [
            'name' => 'Updated Field',
            'crop_type' => 'Corn',
            'planting_date' => $field->planting_date->format('Y-m-d'),
            'current_stage' => $field->current_stage,
            'assigned_agent_id' => $agent->id,
        ]);

        $response->assertRedirect('/fields/' . $field->id);
        $this->assertDatabaseHas('fields', [
            'id' => $field->id,
            'assigned_agent_id' => $agent->id,
        ]);
    }

    public function test_field_cannot_be_assigned_to_non_agent()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        // Another admin or user that is not field_agent
        $otherUser = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post('/fields', [
            'name' => 'Test Field',
            'crop_type' => 'Wheat',
            'planting_date' => now()->subDays(5)->format('Y-m-d'),
            'current_stage' => 'Planted',
            'assigned_agent_id' => $otherUser->id,
        ]);

        $response->assertSessionHasErrors('assigned_agent_id');
        $this->assertDatabaseMissing('fields', [
            'name' => 'Test Field',
        ]);
    }

    public function test_agent_cannot_assign_fields()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);
        $anotherAgent = User::factory()->create(['role' => 'field_agent']);

        $response = $this->actingAs($agent)->post('/fields', [
            'name' => 'Test Field',
            'crop_type' => 'Wheat',
            'planting_date' => now()->subDays(5)->format('Y-m-d'),
            'current_stage' => 'Planted',
            'assigned_agent_id' => $anotherAgent->id,
        ]);

        $response->assertStatus(403);
    }
}
