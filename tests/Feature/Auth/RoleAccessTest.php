<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_field_agent_cannot_access_admin_dashboard(): void
    {
        $agent = User::factory()->fieldAgent()->create();

        $response = $this->actingAs($agent)->get(route('admin.dashboard'));

        // Middleware EnsureUserIsAdmin aborts with 403
        $response->assertStatus(403);
    }

    public function test_field_agent_can_access_agent_dashboard(): void
    {
        $agent = User::factory()->fieldAgent()->create();

        $response = $this->actingAs($agent)->get(route('agent.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('agent.dashboard');
    }

    public function test_unauthenticated_user_redirected_to_login_when_accessing_dashboards(): void
    {
        $response1 = $this->get(route('admin.dashboard'));
        $response1->assertRedirect(route('login'));

        $response2 = $this->get(route('agent.dashboard'));
        $response2->assertRedirect(route('login'));
    }
}
