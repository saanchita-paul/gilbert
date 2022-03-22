<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use DB;

class AddWaterServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $insert_data = [];

        $connectionApplications = $this->getNonWaterApplications();

        foreach ($connectionApplications as $application) {
            $data = [
                'service_type' => 'water',
                'status' => ConnectionService::STATUS_EA_PROCESSINF,
                'connection_application_id' => $application->id,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $insert_data[] = $data;
        }

        $insert_data = collect($insert_data);

        $chunks = $insert_data->chunk(500);

        foreach ($chunks as $chunk) {
            \DB::table('connection_services')->insert($chunk->toArray());
        }
    }

    /**
     * @return array
     */
    private function getNonWaterApplications(): array
    {
        return DB::select(
            "SELECT ca.id FROM connection_applications ca
                WHERE NOT EXISTS (
                    SELECT *
                    FROM connection_services cs
                    WHERE cs.service_type = 'water'
                        AND cs.connection_application_id = ca.id 
                )"
        );
    }

}
