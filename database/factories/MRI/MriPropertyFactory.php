<?php

namespace Database\Factories\MRI;

use App\Models\MriProperty;
use Illuminate\Database\Eloquent\Factories\Factory;

class MriPropertyFactory extends Factory
{
    protected $model = MriProperty::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address_line_1' => 'Damien Street',
            'address_line_2' => '',
            'suburb' => 'Leopold',
            'state' => 'VIC',
            'post_code' => '3224',
            'country' => 'AUSTRALIA',
            'street_number' => '50',
            'management_type' => 'Residential',
        ];
    }
}
