<?php


namespace App\Services\DuplicateApplication;


use App\Interfaces\DuplicateApplication\DuplicateApplicationInterface;
use App\Models\ConnectionApplication;

class PhoneDuplicationApplicationService implements DuplicateApplicationInterface
{
    /**
     * @var string
     */
    private string $phone;

    public function __construct(string $phone)
    {
        $this->phone = $phone;
    }

    public function updateDuplicationGroupId() :static
    {
        // TODO: Implement updateDuplicationGroupId() method.
    }


    public function findDuplicatedApplicationGroupId(): static
    {
        // TODO: Implement findDuplicatedApplicationGroupId() method.
    }

    public function handle(): void
    {
        // TODO: Implement handle() method.
    }
    private function findEmailDuplicationApplicationBuilder(): static
    {
        $this->builder =  ConnectionApplication::whereRaw("lower(REPLACE(`email`, ' ', '')) = ? ", $this->email);
        return $this;
    }
}
