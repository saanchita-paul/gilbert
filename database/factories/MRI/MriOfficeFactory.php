<?php

namespace Database\Factories\MRI;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MriOffice;
use Illuminate\Support\Carbon;

class MriOfficeFactory extends Factory
{
    protected $model = MriOffice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'application_id' => config('mri.app_id'),
            'key' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
            'company_name' => $this->faker->company,
            'activation_date' => Carbon::now()->format('Y-m-d'),
        ];
    }
}
