<?php

namespace Database\Factories\MRI;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MriAgent;
use App\Models\MriOffice;

class MriAgentFactory extends Factory
{
    protected $model = MriAgent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'agent_id' => vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4) ),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email_address' => $this->faker->unique()->safeEmail(),
            'mobile_phone_number' => $this->faker->numerify('+614########'),
            'roles' => 'Inspecting Agent,Property Manager',
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (MriAgent $mriAgent) {
            $mriOffice = MriOffice::orderBy('id', 'desc')->first();
            if (!$mriOffice) {
                $mriOffice = MriOffice::factory()->create();
            }
            $mriAgent->mri_office_id = $mriOffice->id;
        });
    }
}
