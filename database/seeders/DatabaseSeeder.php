<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Seeders are called in dependency order. More seeders will be
     * added in later phases (fields, field updates, etc.).
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // Phase 3+: FieldSeeder::class,
            // Phase 5+: FieldUpdateSeeder::class,
        ]);
    }
}
