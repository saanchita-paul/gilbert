<?php

namespace Database\Factories;

use App\Models\ConnectionApplication;
use App\Models\Identification;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

class IdentificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Identification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $applications = ConnectionApplication::all();
        return [

            'connection_application_id' => $this->faker->unique->numberBetween(1,50),
            'card_number' => $this->faker->creditCardNumber,
            'state' => $this->faker->streetName,
            'country' => $this->faker->country,
            'card_color' => $this->faker->colorName,
            'special_number' => $this->faker->numberBetween(1,9),
            'expire_date' => $this->faker->creditCardExpirationDate
        ];
    }
}
