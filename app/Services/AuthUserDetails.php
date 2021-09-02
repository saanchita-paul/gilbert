<?php
namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Encryption\Encrypter;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\Permission\Models\Role;

class AuthUserDetails
{

    /**
     * Getting authenticated user details
     *
     * @return array
     */
    #[ArrayShape(['user' => 'array', 'bot_access_token' => 'string'])]
    public function toArray(): array
    {
        /** @var User $user */
        $user = auth()->user();

        return [
            'user' => array_merge($user->toArray(), $this->getUserRolesAndPermissions($user),
                $this->getOfficeDetails($user), $this->getProfileDetails($user)),
            'bot_access_token' => $this->getBotAuthKey($user)
        ];
    }

    /**
     * getting user roles & permissions
     *
     * @param User $user
     *
     * @return array
     */
    #[ArrayShape(['roles' => "array", 'permissions' => "array"])]
    public function getUserRolesAndPermissions(User $user): array
    {
        $roles = $user->roles()->with('permissions')->get();

        $permissions = $roles->map(function (Role $role) {
            return $role->permissions->pluck('name')->toArray();
        })->toArray();

        return [
            'roles' => $roles->pluck('name')->toArray(),
            'permissions' => array_shift($permissions)
        ];
    }

    /**
     * Generating bot access token
     *
     * @param User $user
     *
     * @return string
     */
    private function getBotAuthKey(User $user): string
    {
        $data = [
            'expired_at' => Carbon::now()->addMinutes((int) config('session.lifetime'))->timestamp,
            'access_key' => config('bot.access_key'),
            'user_email' => $user->email
        ];

        $crypt = new Encrypter( config('bot.encryption_key'), 'AES-128-CBC');
        return $crypt->encrypt($data, true);
    }

    public function getOfficeDetails(User $user)
    {
        if ($user->profile_type === User::PROFILE_TYPE_AGENT) {
            return ['office' => $user->profile->office];
        } else {
            return ['office' => null];
        }
    }
    public function getProfileDetails(User $user)
    {
        return ['profile' => $user->profile];
    }

    public function getUserByEmail($email){
        return User::where('email', $email)->get();
    }
}
