<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\FieldUpdate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $agent1 = User::where('email', 'agent1@smartseason.test')->first();
        $agent2 = User::where('email', 'agent2@smartseason.test')->first();

        // 1. ACTIVE FIELD - Recently updated (Agent 1)
        $field1 = Field::updateOrCreate(
            ['name' => 'North Valley Maize'],
            [
                'crop_type' => 'Maize',
                'planting_date' => Carbon::now()->subDays(45)->format('Y-m-d'),
                'current_stage' => 'Growing',
                'assigned_agent_id' => $agent1->id,
                'created_by' => $admin->id,
                'last_observation_at' => Carbon::now()->subDays(2),
            ]
        );

        FieldUpdate::updateOrCreate(
            [
                'field_id' => $field1->id,
                'observed_at' => Carbon::now()->subDays(15)->startOfDay(),
            ],
            [
                'updated_by' => $agent1->id,
                'previous_stage' => 'Planted',
                'new_stage' => 'Growing',
                'note' => 'Crop looking healthy after recent rains.',
            ]
        );

        FieldUpdate::updateOrCreate(
            [
                'field_id' => $field1->id,
                'observed_at' => Carbon::now()->subDays(2)->startOfDay(),
            ],
            [
                'updated_by' => $agent1->id,
                'previous_stage' => 'Growing',
                'new_stage' => 'Growing',
                'note' => 'Standard check. No issues found.',
            ]
        );

        // 2. AT RISK FIELD - Stale (No updates for > 7 days) (Agent 1)
        $field2 = Field::updateOrCreate(
            ['name' => 'East Ridge Wheat'],
            [
                'crop_type' => 'Wheat',
                'planting_date' => Carbon::now()->subDays(60)->format('Y-m-d'),
                'current_stage' => 'Growing',
                'assigned_agent_id' => $agent1->id,
                'created_by' => $admin->id,
                'last_observation_at' => Carbon::now()->subDays(10),
            ]
        );

        FieldUpdate::updateOrCreate(
            [
                'field_id' => $field2->id,
                'observed_at' => Carbon::now()->subDays(30)->startOfDay(),
            ],
            [
                'updated_by' => $agent1->id,
                'previous_stage' => 'Planted',
                'new_stage' => 'Growing',
                'note' => 'First stage of growth confirmed.',
            ]
        );

        FieldUpdate::updateOrCreate(
            [
                'field_id' => $field2->id,
                'observed_at' => Carbon::now()->subDays(10)->startOfDay(),
            ],
            [
                'updated_by' => $agent1->id,
                'previous_stage' => 'Growing',
                'new_stage' => 'Growing',
                'note' => 'Minor pest activity noted, monitoring closely.',
            ]
        );

        // 3. COMPLETED FIELD - Harvested (Agent 2)
        $field3 = Field::updateOrCreate(
            ['name' => 'South Plateau Soy'],
            [
                'crop_type' => 'Soybeans',
                'planting_date' => Carbon::now()->subDays(120)->format('Y-m-d'),
                'current_stage' => 'Harvested',
                'assigned_agent_id' => $agent2->id,
                'created_by' => $admin->id,
                'last_observation_at' => Carbon::now()->subDays(1),
            ]
        );

        FieldUpdate::updateOrCreate(
            [
                'field_id' => $field3->id,
                'observed_at' => Carbon::now()->subDays(5)->startOfDay(),
            ],
            [
                'updated_by' => $agent2->id,
                'previous_stage' => 'Growing',
                'new_stage' => 'Ready',
                'note' => 'Ready for harvest.',
            ]
        );

        FieldUpdate::updateOrCreate(
            [
                'field_id' => $field3->id,
                'observed_at' => Carbon::now()->subDays(1)->startOfDay(),
            ],
            [
                'updated_by' => $agent2->id,
                'previous_stage' => 'Ready',
                'new_stage' => 'Harvested',
                'note' => 'Harvest complete. Yield within expected range.',
            ]
        );

        // 4. UNASSIGNED FIELD - Active
        Field::updateOrCreate(
            ['name' => 'Western Acres Cotton'],
            [
                'crop_type' => 'Cotton',
                'planting_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'current_stage' => 'Planted',
                'assigned_agent_id' => null,
                'created_by' => $admin->id,
                'last_observation_at' => null,
            ]
        );

        // 5. AT RISK FIELD - Stuck in stage (Growing for > 45 days) (Agent 2)
        $field4 = Field::updateOrCreate(
            ['name' => 'River Basin Barley'],
            [
                'crop_type' => 'Barley',
                'planting_date' => Carbon::now()->subDays(100)->format('Y-m-d'),
                'current_stage' => 'Growing',
                'assigned_agent_id' => $agent2->id,
                'created_by' => $admin->id,
                'last_observation_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(60), // Manually set to simulate stuck stage
            ]
        );

        FieldUpdate::updateOrCreate(
            [
                'field_id' => $field4->id,
                'observed_at' => Carbon::now()->subDays(60)->startOfDay(),
            ],
            [
                'updated_by' => $agent2->id,
                'previous_stage' => 'Planted',
                'new_stage' => 'Growing',
                'note' => 'Entered growing stage.',
            ]
        );

        FieldUpdate::updateOrCreate(
            [
                'field_id' => $field4->id,
                'observed_at' => Carbon::now()->subDays(3)->startOfDay(),
            ],
            [
                'updated_by' => $agent2->id,
                'previous_stage' => 'Growing',
                'new_stage' => 'Growing',
                'note' => 'Still growing, slower than expected.',
            ]
        );

        // 6. RANDOM EXTRA FIELDS (Manual to avoid Faker dependency in production)
        if (Field::count() < 10) {
            $extraFields = [
                ['name' => 'Southern Plains Rice', 'crop' => 'Rice'],
                ['name' => 'Hillside Vineyards', 'crop' => 'Grapes'],
                ['name' => 'Coastal Citrus Grove', 'crop' => 'Oranges'],
                ['name' => 'Highland Potato Farm', 'crop' => 'Potatoes'],
                ['name' => 'Delta Cotton Field', 'crop' => 'Cotton'],
            ];

            foreach ($extraFields as $data) {
                $field = Field::updateOrCreate(
                    ['name' => $data['name']],
                    [
                        'crop_type' => $data['crop'],
                        'planting_date' => Carbon::now()->subDays(rand(10, 100))->format('Y-m-d'),
                        'current_stage' => 'Growing',
                        'assigned_agent_id' => rand(0, 1) ? $agent1->id : $agent2->id,
                        'created_by' => $admin->id,
                        'last_observation_at' => Carbon::now()->subDays(rand(1, 5)),
                    ]
                );

                FieldUpdate::updateOrCreate(
                    [
                        'field_id' => $field->id,
                        'observed_at' => Carbon::parse($field->planting_date)->addDays(5)->startOfDay(),
                    ],
                    [
                        'updated_by' => $field->assigned_agent_id,
                        'previous_stage' => 'Planted',
                        'new_stage' => 'Growing',
                        'note' => 'Initial seeded observation.',
                    ]
                );
            }
        }
    }
}
