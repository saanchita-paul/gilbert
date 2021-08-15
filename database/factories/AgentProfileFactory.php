<?php

namespace Database\Factories;

use App\Models\AgentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgentProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AgentProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'f_id_12' => $this->faker->numberBetween(100, 900),
//            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber()
        ];
    }
}
