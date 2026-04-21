<?php

namespace Tests\Feature\Fields;

use Tests\TestCase;
use App\Models\Field;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FieldStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_field_index_displays_status_badges(): void
    {
        $activeField = Field::factory()->create([
            'name' => 'Active Field',
            'current_stage' => 'Planted'
        ]);

        $harvestedField = Field::factory()->create([
            'name' => 'Completed Field',
            'current_stage' => 'Harvested'
        ]);

        $response = $this->actingAs($this->admin)->get(route('fields.index'));

        $response->assertStatus(200);
        $response->assertSee('Active');
        $response->assertSee('Completed');
    }

    public function test_field_show_displays_status_and_reason(): void
    {
        $field = Field::factory()->create([
            'name' => 'Detail Field',
            'current_stage' => 'Growing',
            'created_at' => now()->subDays(8) // Should be At Risk
        ]);

        $response = $this->actingAs($this->admin)->get(route('fields.show', $field));

        $response->assertStatus(200);
        $response->assertSee('At Risk');
        $response->assertSee('No updates received in 8 days');
    }
}
