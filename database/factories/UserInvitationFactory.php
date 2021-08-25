<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserInvitation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserInvitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::query()->where('email', 'sarwar.sikder@enkaizen.com')->first();
        return [
            'token_code' => $this->generateToken(),
            'email' => $user->email,
            'user_id' => $user->id,
            'valid_till' => Carbon::now()->addHour(72)->format('Y-m-d H:i:s')
        ];
    }

    protected function generateToken()
    {
        return Str::random(30);
    }
}
