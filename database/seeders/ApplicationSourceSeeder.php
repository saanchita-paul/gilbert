<?php

namespace Database\Seeders;

use App\Models\ExternalSource;
use App\Models\ConnectionApplication;
use Illuminate\Database\Seeder;

class ApplicationSourceSeeder extends Seeder
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
                'logo' => '/assets/images/icons/company/hood.png',
                'table_name' => null,
                'source_id' => ConnectionApplication::SOURCE_HOOD,
                'order' => 1,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@hoodai.com',
                'password' => bcrypt('123456'),
                'source_type' => 'hood_ai',
                'name' => 'Hood.AI',
                'logo' => '/assets/images/icons/company/hood.png',
                'table_name' => null,
                'source_id' => ConnectionApplication::SOURCE_HOOD_LEAD,
                'order' => 2,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@foxie.com',
                'password' => bcrypt('123456'),
                'source_type' => 'foxie',
                'name' => 'Foxie CRM',
                'logo' => '/assets/images/icons/company/foxie.png',
                'table_name' => null,
                'source_id' => ConnectionApplication::SOURCE_FOXIE,
                'order' => 3,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@ignite.com',
                'password' => bcrypt('123456'),
                'source_type' => 'ignite',
                'name' => 'Ignite',
                'logo' => '/assets/images/icons/company/ignite.png',
                'table_name' => null,
                'source_id' => ConnectionApplication::SOURCE_IGNITE,
                'order' => 4,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@ourproperty.com',
                'password' => bcrypt('123456'),
                'source_type' => 'our_property',
                'name' => 'Our Property',
                'logo' => '/assets/images/icons/company/our-property.png',
                'table_name' => null,
                'source_id' => ConnectionApplication::SOURCE_OUR_PROPERTY,
                'order' => 5,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@propertyme.com',
                'password' => bcrypt('123456'),
                'source_type' => 'property_me',
                'name' => 'PropertyMe',
                'logo' => '/assets/images/icons/company/propertyMe.png',
                'table_name' => null,
                'source_id' => ConnectionApplication::SOURCE_PROPERTY_ME,
                'order' => 6,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@tapp.com',
                'password' => bcrypt('123456'),
                'source_type' => 't_app',
                'name' => 'TApp',
                'logo' => '/assets/images/icons/company/tapp.png',
                'table_name' => null,
                'source_id' => ConnectionApplication::SOURCE_T_APP,
                'order' => 7,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@mri.com',
                'password' => bcrypt('123456'),
                'source_type' => 'mri',
                'name' => 'MRI',
                'logo' => '/assets/images/icons/company/mri.png',
                'table_name' => null,
                'source_id' => ConnectionApplication::SOURCE_MRI,
                'order' => 8,
                'is_active' => true,
                'default_office_id' => 1,
            ],
            [
                'email' => 'support@hutly.com',
                'password' => bcrypt('123456'),
                'source_type' => 'hutly',
                'name' => 'Hutly',
                'logo' => '/assets/images/icons/company/hutly.png',
                'table_name' => null,
                'source_id' => ConnectionApplication::SOURCE_HUTLY,
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
