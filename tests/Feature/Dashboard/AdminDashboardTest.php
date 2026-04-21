<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use App\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_admin_can_view_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('summary');
        $response->assertSee('Admin Dashboard');
    }

    /** @test */
    public function test_agent_cannot_view_admin_dashboard()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);

        $response = $this->actingAs($agent)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }
}
