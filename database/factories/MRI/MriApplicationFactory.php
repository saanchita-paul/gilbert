<?php

namespace Database\Factories\MRI;

use App\Models\MriOffice;
use App\Models\MriApplication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class MriApplicationFactory extends Factory
{
    protected $model = MriApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tenancy_id' => vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
            'title' => $this->faker->title(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email_address' => $this->faker->unique()->safeEmail(),
            'mobile_phone_number' => $this->faker->numerify('+614########'),
            'property' => vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4) ),
            'lease_start_date' => Carbon::now()->format('Y-m-d'),
            'is_marketing' => rand(0,1),
        ];
    }

    public function withAuthorizedPerson()
    {
        return $this->state(function (array $attributes) {
            return [
                'authorized_title' => $this->faker->title(),
                'authorized_first_name' => $this->faker->firstName(),
                'authorized_last_name' => $this->faker->lastName(),
                'authorized_email_address' => $this->faker->unique()->safeEmail(),
                'authorized_mobile_phone_number' => $this->faker->numerify('+614########'),
            ];
        });
    }

    public function configure()
    {
        return $this->afterMaking(function (MriApplication $mriApplication) {
            $mriOffice = MriOffice::orderBy('id', 'desc')->first();
            if (!$mriOffice) {
                $mriOffice = MriOffice::factory()->create();
            }
            $mriApplication->mri_office_id = $mriOffice->id;
        });
    }
}
