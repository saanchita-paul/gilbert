<?php

namespace Database\Factories;

use App\Models\Agency;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgencyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Agency::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $index = rand(0, 1);
        $types = [Agency::TYPE_INDEPENDENT, Agency::TYPE_FRANCHISED];
        return [
            'name' => $this->faker->company(),
            'type' => $types[$index]
        ];
    }
}
