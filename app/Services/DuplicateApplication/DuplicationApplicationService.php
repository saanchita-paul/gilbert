<?php


namespace App\Services\DuplicateApplication;


class DuplicationApplicationService
{

    private int $connectionApplicationId;

    public function __construct(int $id)
    {
        $this->connectionApplicationId = $id;
    }

    public function findAndUpdateDuplicatedLead()
    {
        $emailDuplicatedServices = new EmailDuplicatedApplicationService($this->connectionApplicationId);
        $addressDuplicatedServices = new AddressDuplicationApplicationService($this->connectionApplicationId);
        $phoneDuplicatedServices = new PhoneDuplicationApplicationService($this->connectionApplicationId);

    }

}
