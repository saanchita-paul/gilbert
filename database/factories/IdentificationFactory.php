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
        return [
            'type' => Identification::TYPE_PASSPORT,
            'card_number' => $this->faker->creditCardNumber,
            'country' => $this->faker->country,
            'expire_date' => $this->faker->creditCardExpirationDate
        ];
    }
}
