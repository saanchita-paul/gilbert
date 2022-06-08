<?php

namespace Database\Factories;

use App\Models\AppCloseReason;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppCloseReaseonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AppCloseReason::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' => $this->faker->name()
        ];
    }
}
