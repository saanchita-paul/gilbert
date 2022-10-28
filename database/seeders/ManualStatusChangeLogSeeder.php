<?php

namespace Database\Seeders;

use App\Models\ManualStatusChangeLog;
use Illuminate\Database\Seeder;

class ManualStatusChangeLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $logs = [
            [
                'connection_application_id' => 5087,
                'changed_by' => 1,
                'title' => 'Status Change by Admin',
                'status_change_reason' => 'Test reason.',
                'user_role' => 'hood_admin',
                'data' => json_encode([
                    'new_status' => [
                        'application' => 'Closed',
                        'power' => 'Accepted',
                        'gas' => 'Accepted',
                        'water' => 'Accepted',
                        'internet' => 'N/A',
                    ],
                    'old_status' => [
                        'application' => 'Assigned',
                        'power' => 'In Progress',
                        'gas' => 'In Progress',
                        'water' => 'In Progress',
                        'internet' => 'N/A',
                    ],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'connection_application_id' => 5086,
                'changed_by' => 1,
                'title' => 'Status Change by Admin',
                'status_change_reason' => 'Test reason.',
                'user_role' => 'hood_admin',
                'data' => json_encode(
                    [
                        'new_status' => [
                            'application' => 'Closed',
                            'power' => 'Accepted',
                            'gas' => 'Accepted',
                            'water' => 'Accepted',
                            'internet' => 'N/A',
                        ],
                        'old_status' => [
                            'application' => 'Assigned',
                            'power' => 'In Progress',
                            'gas' => 'In Progress',
                            'water' => 'In Progress',
                            'internet' => 'N/A',
                        ],
                    ]
                ),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        ManualStatusChangeLog::insert($logs);

        $this->command->info('Manual status change logs seeded.');
    }
}
