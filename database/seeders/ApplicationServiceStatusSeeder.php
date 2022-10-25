<?php

namespace Database\Seeders;

use App\Models\ApplicationServiceStatus;
use Illuminate\Database\Seeder;

class ApplicationServiceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'type' => 'application',
                'display_text' => 'Unassigned',
                'display_text_alias' => 'Unassigned',
                'status_value' => 'unassigned',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'application',
                'display_text' => 'Assigned',
                'display_text_alias' => 'Assigned',
                'status_value' => 'assigned',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'application',
                'display_text' => 'Escalated',
                'display_text_alias' => 'Escalated',
                'status_value' => 'escalated',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'application',
                'display_text' => 'Submitted',
                'display_text_alias' => 'Submitted',
                'status_value' => 'submitted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'application',
                'display_text' => 'Accepted',
                'display_text_alias' => 'Accepted',
                'status_value' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'application',
                'display_text' => 'Rejected',
                'display_text_alias' => 'Rejected',
                'status_value' => 'rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'application',
                'display_text' => 'Processing',
                'display_text_alias' => 'Processing',
                'status_value' => 'processing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'application',
                'display_text' => 'Closed',
                'display_text_alias' => 'Closed',
                'status_value' => 'closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'service',
                'display_text' => 'Not Submitted',
                'display_text_alias' => 'Not Submitted',
                'status_value' => 'not_submitted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'service',
                'display_text' => 'Accepted',
                'display_text_alias' => 'Accepted',
                'status_value' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'service',
                'display_text' => 'In Progress',
                'display_text_alias' => 'In Progress',
                'status_value' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'service',
                'display_text' => 'Manual Processing',
                'display_text_alias' => 'Manual Processing',
                'status_value' => 'manual_processing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'service',
                'display_text' => 'Rejected',
                'display_text_alias' => 'Rejected',
                'status_value' => 'rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'service',
                'display_text' => 'Manual Processing',
                'display_text_alias' => 'Manual Processing',
                'status_value' => 'manual_processing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'service',
                'display_text' => 'Not Selected',
                'display_text_alias' => 'Not Selected',
                'status_value' => 'not_selected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        ApplicationServiceStatus::truncate();
        ApplicationServiceStatus::insert($statuses);

        $this->command->info('ApplicationServiceStatusSeeder: Seeded application_service_statuses table.');
    }
}
