<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Field;
use App\Models\User;

class FieldUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigned_agent_can_submit_update()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);
        $field = Field::factory()->create([
            'assigned_agent_id' => $agent->id,
            'current_stage' => 'Planted'
        ]);

        $response = $this->actingAs($agent)->post(route('fields.updates.store', $field), [
            'new_stage' => 'Growing',
            'note' => 'Looking good',
            'observed_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('fields.show', $field));
        $this->assertDatabaseHas('field_updates', [
            'field_id' => $field->id,
            'new_stage' => 'Growing',
            'note' => 'Looking good',
            'updated_by' => $agent->id,
        ]);
        $this->assertEquals('Growing', $field->refresh()->current_stage);
    }

    public function test_unassigned_agent_cannot_submit_update()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);
        $otherAgent = User::factory()->create(['role' => 'field_agent']);
        $field = Field::factory()->create([
            'assigned_agent_id' => $agent->id,
        ]);

        $response = $this->actingAs($otherAgent)->post(route('fields.updates.store', $field), [
            'new_stage' => 'Growing',
            'observed_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_submit_update_for_any_field()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $field = Field::factory()->create(['current_stage' => 'Planted']);

        $response = $this->actingAs($admin)->post(route('fields.updates.store', $field), [
            'new_stage' => 'Ready',
            'observed_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('fields.show', $field));
        $this->assertEquals('Ready', $field->refresh()->current_stage);
    }

    public function test_update_validation_requires_valid_stage()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);
        $field = Field::factory()->create(['assigned_agent_id' => $agent->id]);

        $response = $this->actingAs($agent)->post(route('fields.updates.store', $field), [
            'new_stage' => 'InvalidStage',
            'observed_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertSessionHasErrors('new_stage');
    }

    public function test_update_validation_prevents_future_observation_date()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);
        $field = Field::factory()->create(['assigned_agent_id' => $agent->id]);

        $response = $this->actingAs($agent)->post(route('fields.updates.store', $field), [
            'new_stage' => 'Growing',
            'observed_at' => now()->addDay()->format('Y-m-d H:i:s'),
        ]);

        $response->assertSessionHasErrors('observed_at');
    }
}
