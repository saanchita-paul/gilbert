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
        'street_name',
        'city',
        'post_code',
        'country',
        'state'

        ];
    public const DUPLICATED_DATA_ARRAY = [
        'email' => '',
        'phone' => '',
        'unit_number' => '',
        'street_number' => '',
        'street_name' => '',
        'city' => '',
        'post_code' => '',
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
        $this->streetName = $duplicatedKeyData['street_name'] ?? '';
        $this->city = $duplicatedKeyData['city'] ?? '';
        $this->postCode = $duplicatedKeyData['post_code'] ?? '';
        $this->country = $duplicatedKeyData['country'] ?? '';
        $this->state = $duplicatedKeyData['state'] ?? '';
        $this->duplicatedEmailBuilder = null;
        $this->duplicatedPhoneBuilder = null;
        $this->duplicatedAddressBuilder = null;
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

        }

        // run only for new and phone changed lead
        if(!empty($this->phone) && ($this->isNewLead || $this->isPhoneUpdated)) {
            $phoneDuplicatedServices = new PhoneDuplicationApplicationService($this->phone);
            $this->duplicatedPhoneBuilder =  $phoneDuplicatedServices->getBuilder();
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
            $this->duplicatedAddressBuilder = $addressDuplicatedServices->getBuilder();
        }

    }

    /**
     * @param Builder|null $builder
     * @return bool
     */
    private function isSetGroupID(?Builder $builder):bool
    {

        info('database value', ['count' => $builder]);
        if(empty($builder) || $builder->count()< 1) {
            return  false;
        }

        $temporaryBuilder = clone $builder;

        // if connection already in existing group
        $this->duplicatedGroupId = $temporaryBuilder->whereNotNull('duplication_group_id')
            ->pluck('duplication_group_id')->first();

//        info('duplicatedGroupId value 1', ['this->duplicatedGroupId' => $this->duplicatedGroupId]);
        if(!empty( $this->duplicatedGroupId)) {
         return true;
        }

        // if connection already not in existing group
        $this->duplicatedGroupId = $builder->whereNotNull('id')
            ->pluck('id')->first();
//        info('duplicatedGroupId value 2', ['this->duplicatedGroupId' => $this->duplicatedGroupId]);
        return true;

    }

    /**
     * update group id if possible
     */
    private function updateGroupId(): void
    {
        $updateGroupId = false;
        $setGroupId = $this->isSetGroupID($this->duplicatedEmailBuilder);
        if($setGroupId && !$updateGroupId) {

            $updateGroupId = true;
//            info('duplicatedAddressBuilder');
            $this->duplicatedEmailBuilder->update(
                [
                    'is_duplicate' => true,
                    'duplication_group_id'=> $this->duplicatedGroupId
                ]
            );
        }

        $setGroupId = $this->isSetGroupID($this->duplicatedPhoneBuilder);
        if($setGroupId && !$updateGroupId) {
//            info('duplicatedAddressBuilder');
            $updateGroupId = true;
            $this->duplicatedPhoneBuilder->update(
                [
                    'is_duplicate' => true,
                    'duplication_group_id'=> $this->duplicatedGroupId
                ]
            );
        }

        $setGroupId = $this->isSetGroupID($this->duplicatedAddressBuilder);
        if($setGroupId && !$updateGroupId) {
            $this->duplicatedAddressBuilder->update(
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

        if($this->duplicatedGroupId === 'null') {
            $this->duplicatedGroupId = null;
        }

        return $this->duplicatedGroupId;
    }

}
