<?php

namespace Database\Seeders;

use App\Models\ApplicationSource;
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
                'name' => 'Hood Agent Portal',
                'value' => 'hood',
                'logo' => '/assets/images/icons/company/hood.png',
                'app_source_id' => ConnectionApplication::SOURCE_HOOD,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Hood.AI',
                'value' => 'hood_ai',
                'logo' => '/assets/images/icons/company/hood.png',
                'app_source_id' => ConnectionApplication::SOURCE_HOOD_LEAD,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Foxie CRM',
                'value' => 'foxie',
                'logo' => '/assets/images/icons/company/foxie.png',
                'app_source_id' => ConnectionApplication::SOURCE_FOXIE,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Ignite',
                'value' => 'ignite',
                'logo' => '/assets/images/icons/company/ignite.png',
                'app_source_id' => ConnectionApplication::SOURCE_IGNITE,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Our Property',
                'value' => 'our-property',
                'logo' => '/assets/images/icons/company/our-property.png',
                'app_source_id' => ConnectionApplication::SOURCE_OUR_PROPERTY,
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'PropertyMe',
                'value' => 'property_me',
                'logo' => '/assets/images/icons/company/propertyMe.png',
                'app_source_id' => ConnectionApplication::SOURCE_PROPERTY_ME,
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'TApp',
                'value' => 't_app',
                'logo' => '/assets/images/icons/company/tapp.png',
                'app_source_id' => ConnectionApplication::SOURCE_T_APP,
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'MRI',
                'value' => 'mri',
                'logo' => '/assets/images/icons/company/propertyMe.png',
                'app_source_id' => ConnectionApplication::SOURCE_MRI,
                'order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Hutly',
                'value' => 'hutly',
                'logo' => '/assets/images/icons/company/hutly.png',
                'app_source_id' => ConnectionApplication::SOURCE_HUTLY,
                'order' => 9,
                'is_active' => true,
            ],
        ];

        ApplicationSource::query()->truncate();
        foreach ($sources as $source) {
            ApplicationSource::query()->create($source);
        }
    }
}
