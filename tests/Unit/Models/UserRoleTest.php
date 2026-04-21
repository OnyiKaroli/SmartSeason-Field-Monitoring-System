<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    public function test_isAdmin_returns_true_for_admin_role(): void
    {
        $user = new User(['role' => 'admin']);
        
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isFieldAgent());
    }

    public function test_isFieldAgent_returns_true_for_field_agent_role(): void
    {
        $user = new User(['role' => 'field_agent']);
        
        $this->assertTrue($user->isFieldAgent());
        $this->assertFalse($user->isAdmin());
    }
}
