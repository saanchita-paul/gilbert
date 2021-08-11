<?php

namespace Database\Factories;

use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConnectionApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConnectionApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $boolean_type = rand(0, 1);
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'dob' => $this->faker->date(),
            'moving_date' => $this->faker->dateTime(),
            'street_address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'state' => $this->faker->streetName(),
            'country' => $this->faker->country(),
            'additional_instruction' => $this->faker->text(),
            'address_text' => $this->faker->address(),
            'is_email_billing' => $this->faker->boolean(),
            'property_type' => $this->faker->boolean(),
            'has_life_support' => $this->faker->boolean(),
            'has_solar' => $this->faker->boolean(),
            'nmi' => $this->faker->shuffleString(),
            'mirn' => $this->faker->shuffleString(),
            'is_escalated' => $this->faker->boolean(),
            'supplier' => $this->faker->boolean(),
            'plan_type' => $this->faker->boolean(),
            'status' => $this->faker->boolean(),
        ];
    }
}
