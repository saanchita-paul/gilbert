<?php


namespace App\Services\DuplicateApplication;


use App\Interfaces\DuplicateApplication\DuplicateApplicationInterface;
use App\Models\ConnectionApplication;
use App\Services\FullTextSearch\FullTextQueryInterface;
use App\Services\FullTextSearch\FullTextSearchInterface;
use Illuminate\Database\Eloquent\Builder;

class AddressDuplicationApplicationService implements DuplicateApplicationInterface
{

    /**
     * @var FullTextQueryInterface[]
     */
    private array $searchQueries = [];

    /**
     * @var Builder
     */
    private Builder $builder;

    private string $unitNumber;
    private string $streetNumber;
    private string $streetName;
    private string $city;
    private string $state;
    private string $postCode;
    private string $country;


    /**
     * AddressDuplicationApplicationService constructor.
     * @param string $unitNumber
     * @param string $streetNumber
     * @param string $streetName
     * @param string $city
     * @param string $postCode
     * @param string $state
     * @param string $country
     */
    public function __construct(
        string $unitNumber,
        string $streetNumber,
        string $streetName,
        string $city,
        string $postCode,
        string $state,
        string $country
    )
    {
        $this->unitNumber = strtolower($unitNumber);
        $this->streetNumber = $streetNumber;
        $this->streetName = $streetName;
        $this->city = $city;
        $this->state = $state;
        $this->postCode = $postCode;
        $this->country = $country;
        $this->builder = ConnectionApplication::query();
    }

    /**
     * @return Builder
     */
    public function getBuilder(): Builder
    {
        $fts = resolve(FullTextSearchInterface::class);
        $this->createFullTextQueries();
        $this->builder = $fts->applyAndSearches($this->builder, queries: $this->searchQueries);
        $this->applyUnitSearch();
        return  $this->builder;
    }

    /**
     * initiate full text address query
     * @return void
     */
    private function createFullTextQueries(): void
    {
        $address = $this->getAddress();

        /** @var FullTextQueryInterface $query */
        $query = resolve(FullTextQueryInterface::class);
        if (!empty($address)) {
            $index = 'unit_number,street_number,street_name_only,city,postcode,state,country,street_address,address_text';
            $this->searchQueries[] = $query->createNew(text: $address, index: $index);

        }
    }

    /**
     * prepare address using separated address part
     * @return string
     */
    private function getAddress():string
    {
        $address = $this->streetName.' '.
        $address = $this->streetNumber.' '.
        $address = $this->city.' '.
        $address = $this->postCode.' '.
        $address = $this->state.' '.
        $address = $this->country.' ';
        return trim($address);

    }

    /**
     * apply unit search separately and find the lead where unit id match and
     * update the query builder with related connection application
     * @return void
     */
    private function applyUnitSearch():void
    {
        $duplicatedAddressLead = $this->builder->get();
        $duplicatedAddressLead->filter(function($data)  {

            return empty($this->unitNumber) ||
                $this->unitNumber === 'null' ||
                $this->matchUnitNumber($data->unit_number);
        });

        info('unit number match', [
            $duplicatedAddressLead->pluck('id')->toArray()
        ]);

        $duplicatedAddressLeadId = $duplicatedAddressLead->pluck('id')->toArray();
        $this->builder = $this->builder->whereIn('id', $duplicatedAddressLeadId);
    }


    /**
     * @param string $ca_unit_number
     * @return bool
     */
    private function matchUnitNumber(?string $ca_unit_number):bool
    {
        $ca_unit_number = strtolower($ca_unit_number);
        // if user address does not contain any unit value then all match address should mark as duplicate
        if(empty(trim($ca_unit_number)) || $ca_unit_number === 'null') {
            return true;
        }

        // exact match checking
        if(trim($ca_unit_number) === trim($this->unitNumber)) {
            return true;
        }

        // find text `unit` exist in the unit number or not


        if($this->extractUnitFromString($ca_unit_number) === $this->extractUnitFromString($this->unitNumber)) {
            return true;
        }

        return false;


    }

    /**
     * this function is trying to find `unit` text is exist or not
     * @param $ca_unit_number
     * @return string
     */
    private function extractUnitFromString(?string $ca_unit_number)
    {

        $pos = stripos($ca_unit_number, 'unit');
        if($pos) {
            return preg_replace('/[^A-Za-z0-9]/', '', substr($ca_unit_number, $pos + 4));
        }
        $ca_unit_number = preg_replace('/[^A-Za-z0-9]/', '', $ca_unit_number);
        if(strlen($ca_unit_number)>1 && $ca_unit_number[0] === 'u') {
            return substr($ca_unit_number, 1);
        }

        return $ca_unit_number;
    }


}
