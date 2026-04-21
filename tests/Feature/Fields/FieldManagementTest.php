<?php

namespace Tests\Feature\Fields;

use App\Models\User;
use App\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_fields_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Field::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('/fields');

        $response->assertStatus(200);
        $response->assertViewIs('fields.index');
        $response->assertViewHas('fields');
    }

    public function test_field_agent_cannot_access_fields_management()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);

        $response = $this->actingAs($agent)->get('/fields');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_a_field()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $fieldData = [
            'name' => 'North Field',
            'crop_type' => 'Corn',
            'planting_date' => now()->format('Y-m-d'),
            'current_stage' => 'Planted',
        ];

        $response = $this->actingAs($admin)->post('/fields', $fieldData);

        $response->assertRedirect('/fields');
        $this->assertDatabaseHas('fields', [
            'name' => 'North Field',
            'crop_type' => 'Corn',
        ]);
    }

    public function test_field_creation_validates_required_data()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/fields', []);

        $response->assertSessionHasErrors(['name', 'crop_type', 'planting_date']);
    }

    public function test_field_creation_prevents_future_planting_dates()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $fieldData = [
            'name' => 'North Field',
            'crop_type' => 'Corn',
            'planting_date' => now()->addDay()->format('Y-m-d'), // Future date
        ];

        $response = $this->actingAs($admin)->post('/fields', $fieldData);

        $response->assertSessionHasErrors(['planting_date']);
    }

    public function test_admin_can_update_a_field()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $field = Field::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($admin)->put('/fields/' . $field->id, [
            'name' => 'New Name',
            'crop_type' => 'Wheat',
            'planting_date' => now()->subDays(10)->format('Y-m-d'),
            'current_stage' => 'Growing',
        ]);

        $response->assertRedirect('/fields/' . $field->id);
        $this->assertDatabaseHas('fields', [
            'id' => $field->id,
            'name' => 'New Name',
            'current_stage' => 'Growing',
        ]);
    }

    public function test_admin_can_delete_a_field()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $field = Field::factory()->create();

        $response = $this->actingAs($admin)->delete('/fields/' . $field->id);

        $response->assertRedirect('/fields');
        $this->assertDatabaseMissing('fields', [
            'id' => $field->id,
        ]);
    }
}
