<?php

namespace Database\Seeders;

use App\Models\ExternalSource;
use App\Models\ConnectionApplication;
use Illuminate\Database\Seeder;

class ExternalSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sources = [
            [
                'email' => 'support@hood.com',
                'password' => bcrypt('123456'),
                'source_type' => 'hood',
                'name' => 'Hood Agent Portal',
                'logo' => 'hood.png',
                'table_name' => null,
                'source_id' => 0,
                'order' => 1,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@hoodai.com',
                'password' => bcrypt('123456'),
                'source_type' => 'hood_ai',
                'name' => 'Hood.AI',
                'logo' => 'hood.png',
                'table_name' => null,
                'source_id' => 10,
                'order' => 2,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@foxie.com',
                'password' => bcrypt('123456'),
                'source_type' => 'foxie',
                'name' => 'Foxie CRM',
                'logo' => 'foxie.png',
                'table_name' => null,
                'source_id' => 1,
                'order' => 3,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@ignite.com',
                'password' => bcrypt('123456'),
                'source_type' => 'ignite',
                'name' => 'Ignite',
                'logo' => 'ignite.png',
                'table_name' => null,
                'source_id' => 2,
                'order' => 4,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@ourproperty.com',
                'password' => bcrypt('123456'),
                'source_type' => 'our_property',
                'name' => 'Our Property',
                'logo' => 'our_property.png',
                'table_name' => null,
                'source_id' => 4,
                'order' => 5,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@propertyme.com',
                'password' => bcrypt('123456'),
                'source_type' => 'property_me',
                'name' => 'PropertyMe',
                'logo' => 'property_me.png',
                'table_name' => null,
                'source_id' => 5,
                'order' => 6,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@tapp.com',
                'password' => bcrypt('123456'),
                'source_type' => 't_app',
                'name' => 'TApp',
                'logo' => 't_app.png',
                'table_name' => null,
                'source_id' => 11,
                'order' => 7,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@mri.com',
                'password' => bcrypt('123456'),
                'source_type' => 'mri',
                'name' => 'MRI',
                'logo' => 'mri.png',
                'table_name' => null,
                'source_id' => 12,
                'order' => 8,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@hutly.com',
                'password' => bcrypt('123456'),
                'source_type' => 'hutly',
                'name' => 'Hutly',
                'logo' => 'hutly.png',
                'table_name' => null,
                'source_id' => 13,
                'order' => 9,
                'is_active' => true,
                'default_office_id' => 1,
            ],
        ];

        ExternalSource::query()->truncate();
        foreach ($sources as $source) {
            ExternalSource::query()->create($source);
        }
    }
}
