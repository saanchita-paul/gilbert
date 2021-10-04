<?php

namespace Database\Factories;

use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Office::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'street_address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'abn' => $this->faker->text('10'),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail()
        ];
    }
}
