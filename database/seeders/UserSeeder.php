<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the demo users.
     *
     * Creates one Admin coordinator and two Field Agents with consistent,
     * predictable credentials suitable for reviewers to use immediately.
     */
    public function run(): void
    {
        // Admin / Coordinator
        User::firstOrCreate(
            ['email' => 'admin@smartseason.test'],
            [
                'name'     => 'Sarah Okonkwo',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Field Agent 1
        User::firstOrCreate(
            ['email' => 'agent1@smartseason.test'],
            [
                'name'     => 'James Adeyemi',
                'password' => Hash::make('password'),
                'role'     => 'field_agent',
            ]
        );

        // Field Agent 2
        User::firstOrCreate(
            ['email' => 'agent2@smartseason.test'],
            [
                'name'     => 'Amara Nwosu',
                'password' => Hash::make('password'),
                'role'     => 'field_agent',
            ]
        );

        $this->command->info('Demo users seeded:');
        $this->command->line('  Admin:  admin@smartseason.test / password');
        $this->command->line('  Agent1: agent1@smartseason.test / password');
        $this->command->line('  Agent2: agent2@smartseason.test / password');
    }
}
