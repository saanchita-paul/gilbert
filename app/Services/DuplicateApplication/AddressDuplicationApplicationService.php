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

    private $connectionId;

    /**
     * @var ConnectionApplication|Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    private ConnectionApplication|null $connectionApplication;

    /**
     * @var mixed
     */
    private $duplicated_address_group_id;

    private array $duplicatedAddressLeadId;



    public function __construct($id)
    {
        $this->connectionId = $id;
        $this->connectionApplication = ConnectionApplication::query()->find($id);
        $this->builder = ConnectionApplication::query();
    }

    public function handle(): void
    {
        $this->findAddressDuplicationApplicationBuilder()
            ->findDuplicatedApplicationGroupId()
            ->updateDuplicationGroupId();
    }

    public function updateDuplicationGroupId():static
    {
        $this->builder->update(['duplicated_address_group_id'=>  $this->duplicated_address_group_id]);
        return $this;
    }

    public function findDuplicatedApplicationGroupId(): static
    {

        $this->duplicated_address_group_id = ConnectionApplication::query()
            ->whereNotNull('duplicated_address_group_id')
            ->whereIn('id', $this->duplicatedAddressLeadId)
            ->pluck('duplicated_address_group_id')->first();

        if(empty($this->duplicated_address_group_id)) {
            $this->duplicated_address_group_id = ConnectionApplication::query()
                ->whereIn('id', $this->duplicatedAddressLeadId)
                ->pluck('id')->first();
        }
        return $this;
    }

    /**
     * @return $this
     */
    private function findAddressDuplicationApplicationBuilder(): static
    {
        $fts = resolve(FullTextSearchInterface::class);
        $this->createFullTextQueries();
        $this->builder = $fts->applyAndSearches($this->builder, queries: $this->searchQueries);
        $this->applyUnitSearch();
        return $this;
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
            $index = 'unit_number,street_number,street_name,city,postcode,state,country,street_address,address_text';
            $this->searchQueries[] = $query->createNew(text: $address, index: $index);

        }
    }

    /**
     * prepare address using separated address part
     * @return string
     */
    private function getAddress():string
    {
        $address = $this->connectionApplication->street_number .' '
            . $this->connectionApplication->street_name. ' '.
            $this->connectionApplication->city .' '.
            $this->connectionApplication->postcode. ' '.
            $this->connectionApplication->state. ' '.
            $this->connectionApplication->country. ' ';
        return trim($address);

    }

    /**
     * apply unit search separately and find the lead where unit id match and
     * update the query builder with related connection application
     * @return void
     */
    private function applyUnitSearch():void
    {
        $unitNumber = $this->connectionApplication->unit_number ?? null;
        $duplicatedAddressLead = $this->builder->get();
        $duplicatedAddressLead->filter(function($data) use ($unitNumber) {

            return $this->extractUnitNumber($data->unit_number) === $this->extractUnitNumber($unitNumber);
        });

        $this->duplicatedAddressLeadId = $duplicatedAddressLead->pluck('id')->toArray();
        $this->builder = $this->builder->whereIn('id', $this->duplicatedAddressLeadId);
    }

    private function extractUnitNumber($unitNumber)
    {
        return preg_replace("/[^0-9]/", '', $unitNumber);
    }



}
