<?php

namespace Database\Factories;

use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\User;
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
        $profile = User::query()->where('email', 'robert@barryplant.com')->first()->profile;
        return [
            'office_id' => $profile->office->id,
            'agency_id' => $profile->office->agency->id,
            'created_by' => $profile->id,
            'title' => 'Mr',
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'phone' => rand(1000000000, 10000000000),
            'tenancy_type' => rand(1, 3),
            'dob' => $this->faker->date(),
            'moving_date' => $this->faker->date(),
            'street_address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'state' => $this->faker->city(),
            'country' => $this->faker->country(),
            'additional_instruction' => $this->faker->sentence(10),
            'address_text' => $this->faker->address(),
            'is_email_billing' => rand(0, 1),
            'property_type' => rand(0, 1),
            'has_solar' => rand(0, 1),
            'has_life_support' => rand(0, 1),
            'status' => ConnectionApplication::STATUS_UNASSIGNED
        ];
    }
}
