<?php

namespace App\Services\Agency;

use App\Models\HoodProfile;
use App\Models\User;

/**
 *
 */
class CreateHoodUserService
{
    /**
     * @var User $user
     */
    private User $user;
    /**
     * @param array $data
     */
    public function __construct(public array $data)
    {
    }

    /**
     * Start
     */
    public function run(): static
    {
        $this->createUser($this->createHoodProfile()->id, HoodProfile::class);

        return $this;
    }

    /**
     * Getting User array with profile and roles
     *
     * @return array
     */
    public function toArray(): array
    {
        $this->user->load(['profile', 'roles']);

        return $this->user->toArray();
    }

    /**
     * @return HoodProfile
     */
    private function createHoodProfile(): HoodProfile
    {
        $profile = new HoodProfile();
        $profile->fill($this->data)->save();
        return $profile;
    }


    /**
     * @param int $profileId
     * @param string $type
     * @return User
     */
    private function createUser(int $profileId, string $type): User
    {
        $this->user = (new User())->fill(array_merge($this->data, [
            'profile_id' => $profileId,
            'profile_type' =>  $type,
            'password' => bcrypt($this->data['password'])
        ]));

        $this->user->save();
        $this->user->assignRole($this->data['roles']);
        return $this->user;
    }
}
