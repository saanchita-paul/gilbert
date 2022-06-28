<?php

namespace Database\Seeders;

use App\Models\AppCloseReason;
use Illuminate\Database\Seeder;

class ApplicationCloseReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $closeReasons = [
            ['value' => 'Customer wants to arrange themselves'],
            ['value' => 'Did not opt in'],
            ['value' => 'Already arranged'],
            ['value' => 'Embedded Network'],
            ['value' => 'Already moved in'],
            ['value' => 'Not taking property'],
            ['value' => 'Late Application'],
            ['value' => 'Translator'],
            ['value' => 'REA referred another provider'],
            ['value' => 'Lease Renewal'],
            ['value' => 'Unserviceable'],
            ['value' => 'DNC'],
            ['value' => 'Hood Actioned'],
            ['value' => 'Wrong person on the application'],
            ['value' => 'Duplicate'],
            ['value' => 'Incorrect Details'],
            ['value' => 'Others'],
        ];

        foreach ($closeReasons as $reason) {
            AppCloseReason::create($reason);
        }
    }
}
