<?php

namespace Database\Factories;

use App\Models\ConnectionService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConnectionServiceFactory extends Factory
{
    private static $types_index = -1;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConnectionService::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        ConnectionServiceFactory::$types_index += 1;
        $types = ['power', 'gas', 'internet', 'water'];
        return [
            'service_type' => $types[ConnectionServiceFactory::$types_index % 4],
        ];
    }
}
