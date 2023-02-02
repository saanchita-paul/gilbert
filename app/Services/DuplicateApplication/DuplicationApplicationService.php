<?php


namespace App\Services\DuplicateApplication;



use Illuminate\Database\Eloquent\Builder;

class DuplicationApplicationService
{

    public const DUPLICATED_FIELD = [
        'email',
        'phone',
        'unit_number',
        'street_number',
        'street_name_only',
        'city',
        'postcode',
        'country',
        'state'

        ];
    public const DUPLICATED_DATA_ARRAY = [
        'email' => '',
        'phone' => '',
        'unit_number' => '',
        'street_number' => '',
        'street_name_only' => '',
        'city' => '',
        'postcode' => '',
        'country' => '',
        'state' => '',
    ];

    /**
     * @var mixed|string
     */
    private $email;
    /**
     * @var mixed
     */
    private $phone;
    /**
     * @var mixed
     */
    private $streetNumber;
    /**
     * @var mixed
     */
    private $city;
    /**
     * @var mixed
     */
    private $streetName;
    /**
     * @var mixed
     */
    private $country;
    /**
     * @var mixed
     */
    private $state;
    /**
     * @var mixed
     */
    private $postCode;
    /**
     * @var mixed|string
     */
    private $unitNumber;
    /**
     * @var false
     */
    private bool $isNewLead;
    /**
     * @var false
     */
    private bool $isPhoneUpdated;
    /**
     * @var false
     */
    private bool $isEmailUpdated;
    /**
     * @var false
     */
    private bool $isAddressUpdate;

    /**
     * @var Builder | null
     */
    private ?Builder $duplicatedEmailBuilder;
    /**
     * @var Builder | null
     */
    private ?Builder $duplicatedPhoneBuilder;
    /**
     * @var Builder | null
     */
    private ?Builder $duplicatedAddressBuilder;
    /**
     * @var mixed
     */
    private $duplicatedGroupId;

    /**
     * @var Builder | null
     */
    private ?Builder $activeBuilder;


    /**
     * DuplicationApplicationService constructor.
     * @param array $duplicatedKeyData
     * @param false $isNewLead
     * @param false $isPhoneUpdated
     * @param false $isEmailUpdated
     * @param false $isAddressUpdate
     */
    public function __construct(
        array $duplicatedKeyData,
        $isNewLead =  false,
        $isPhoneUpdated = false,
        $isEmailUpdated = false,
        $isAddressUpdate = false,
    )
    {
        $this->populatedRequiredField($duplicatedKeyData);
        $this->isNewLead = $isNewLead;
        $this->isPhoneUpdated = $isPhoneUpdated;
        $this->isEmailUpdated = $isEmailUpdated;
        $this->isAddressUpdate = $isAddressUpdate;

    }




    private function populatedRequiredField(array $duplicatedKeyData)
    {
        info('duplicated key', $duplicatedKeyData);
        $this->email = $duplicatedKeyData['email'] ?? '';
        $this->phone = $duplicatedKeyData['phone'] ?? '';
        $this->unitNumber = $duplicatedKeyData['unit_number'] ?? '';
        $this->streetNumber = $duplicatedKeyData['street_number'] ?? '';
        $this->streetName = $duplicatedKeyData['street_name_only'] ?? '';
        $this->city = $duplicatedKeyData['city'] ?? '';
        $this->postCode = $duplicatedKeyData['postcode'] ?? '';
        $this->country = $duplicatedKeyData['country'] ?? '';
        $this->state = $duplicatedKeyData['state'] ?? '';
        $this->duplicatedEmailBuilder = null;
        $this->duplicatedPhoneBuilder = null;
        $this->duplicatedAddressBuilder = null;
        $this->activeBuilder = null;
        $this->duplicatedGroupId = null;
    }

    /**
     * @param bool $shouldCheckEmail
     * @param bool $shouldCheckPhone
     * @param bool $shouldCheckAddress
     */
    private function findAndSetDuplicatedLeadQuery()
    {

        // run only for new and email changed lead
        if(!empty($this->email) && ($this->isNewLead || $this->isEmailUpdated)) {
            $emailDuplicatedServices = new EmailDuplicatedApplicationService($this->email);
            $this->duplicatedEmailBuilder = $emailDuplicatedServices->getBuilder();
            $itemCount = $this->duplicatedEmailBuilder->count();
            info('email duplicated items count'.$itemCount);
            if($itemCount > 0) {
                $this->activeBuilder = $this->duplicatedEmailBuilder;
                return;
            }

        }

        // run only for new and phone changed lead
        if(!empty($this->phone) && ($this->isNewLead || $this->isPhoneUpdated)) {
            $phoneDuplicatedServices = new PhoneDuplicationApplicationService($this->phone);
            $this->duplicatedPhoneBuilder =  $phoneDuplicatedServices->getBuilder();
            $itemCount = $this->duplicatedPhoneBuilder->count();
            info('phone duplicated items count'.$itemCount);
            if($itemCount > 0) {
                $this->activeBuilder = $this->duplicatedPhoneBuilder;
                info('phone duplicated items count '.$itemCount);
                return;
            }
        }

//         run only for new and phone changed lead
        if($this->isNewLead || $this->isAddressUpdate) {
            $addressDuplicatedServices = new AddressDuplicationApplicationService(
                $this->unitNumber,
                $this->streetNumber,
                $this->streetName,
                $this->city,
                $this->postCode,
                $this->state,
                $this->country
            );
            if (empty($addressDuplicatedServices->getAddress())) {
                return;
            }

            $this->duplicatedAddressBuilder = $addressDuplicatedServices->getBuilder();

            $itemCount = $this->duplicatedAddressBuilder->count();
            info('Address duplicated items count '.$itemCount);
            if($itemCount > 0) {
                $this->activeBuilder = $this->duplicatedAddressBuilder;
                return;
            }
        }

    }

    /**
     * @param Builder|null $builder
     * @return void
     */
    private function setGroupID(?Builder $builder):void
    {
        $temporaryBuilder = clone $builder;

        // if connection already in existing group
        $this->duplicatedGroupId = $temporaryBuilder->whereNotNull('duplication_group_id')
            ->pluck('duplication_group_id')->first();

        // if connection already not it in existing group
        if(empty( $this->duplicatedGroupId)) {
            $this->duplicatedGroupId = $builder->pluck('id')->first();
            info('new duplication id '.$this->duplicatedGroupId);
        } else {
            info('parent duplication id '.$this->duplicatedGroupId);
        }
    }

    /**
     * update group id if possible
     */
    private function updateGroupId(): void
    {

        if(!is_null($this->activeBuilder)) {
            $this->setGroupID($this->activeBuilder);
            $this->activeBuilder->update(
                [
                    'is_duplicate' => true,
                    'duplication_group_id'=> $this->duplicatedGroupId
                ]
            );
        }


    }

    public function updateDuplicatedApp()
    {
        $this->findAndSetDuplicatedLeadQuery();
        $this->updateGroupId();

        return $this->duplicatedGroupId;
    }

}
