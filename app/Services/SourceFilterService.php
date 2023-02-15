<?php

namespace App\Services;

class SourceFilterService
{
    public function getSource()
    {
        return $this->getTestDummyData();
    }

    private function getTestDummyData()
    {
        return [
            [
                'id' => 1,
                'name' => 'All Lead Source',
                'value' => '',
                'logo' => '',
                'is_active' => true
            ],
            [
                'id' => 2,
                'name' => 'Hood Agent Portal',
                'value' => 'hood',
                'logo' => '/assets/images/icons/company/hood.png',
                'is_active' => true
            ],
            [
                'id' => 3,
                'name' => 'Hood.AI',
                'value' => 'hood_ai',
                'logo' => '/assets/images/icons/company/hood.png',
                'is_active' => true
            ],
            [
                'id' => 4,
                'name' => 'Foxie CRM',
                'value' => 'foxie',
                'logo' => '/assets/images/icons/company/foxie.png',
                'is_active' => true
            ],
            [
                'id' => 5,
                'name' => 'Ignite',
                'value' => 'ignite',
                'logo' => '/assets/images/icons/company/ignite.png',
                'is_active' => true
            ],
            [
                'id' => 6,
                'name' => 'Our Property',
                'value' => 'our-property',
                'logo' => '/assets/images/icons/company/our-property.png',
                'is_active' => true
            ],
            [
                'id' => 7,
                'name' => 'PropertyMe',
                'value' => 'property_me',
                'logo' => '/assets/images/icons/company/propertyMe.png',
                'is_active' => true
            ],
            [
                'id' => 8,
                'name' => 'TApp',
                'value' => 't_app',
                'logo' => '/assets/images/icons/company/tapp.png',
                'is_active' => true
            ],
            [
                'id' => 9,
                'name' => 'MRI',
                'value' => 'mri',
                'logo' => '/assets/images/icons/company/propertyMe.png',
                'is_active' => true
            ],
            [
                'id' => 10,
                'name' => 'Hutly',
                'value' => 'hutly',
                'logo' => '/assets/images/icons/company/propertyMe.png',
                'is_active' => true
            ],
        ];
    }
}
