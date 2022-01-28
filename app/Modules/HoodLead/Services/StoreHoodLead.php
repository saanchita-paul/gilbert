<?php

namespace HoodLead\Services;

use App\Models\ConnectionApplication;
use App\Models\Office;
use HoodLead\HoodLead;

/**
 *
 */
class StoreHoodLead
{
    /**
     * @var HoodLead
     */
    private HoodLead $hoodLead;

    /**
     * @param array $requestData
     */
    public function __construct(private array $requestData)
    {
        $this->hoodLead = new HoodLead();
    }

    /**
     * @return int
     */
    public function save(): int
    {
        $leadId = $this->dump()->saveApplication();
        $this->hoodLead->connection_application_id = $leadId;
        $this->hoodLead->save();

        return $leadId;
    }

    /**
     * @return int
     */
    private function saveApplication()
    {
        $office = Office::where('name', HoodLead::DEFAULT_OFFICE)->firstOrFail();

        $app = new ConnectionApplication([
            'office_id' => $office->id,
            'agency_id' => $office->agency_id,
            'source' => ConnectionApplication::SOURCE_HOOD_LEAD,
            'status' => ConnectionApplication::STATUS_UNASSIGNED,
            'first_name' => $this->requestData['first_name'] ?? null,
            'last_name' => $this->requestData['last_name'] ?? null,
            'phone' => $this->requestData['phone'] ?? null,
            'email' => $this->requestData['email'] ?? null,
            'postcode' => $this->requestData['postcode'] ?? null,
        ]);
        $app->save();

        return $app->id;
    }

    /**
     * @return $this
     */
    private function dump(): static
    {
        $this->hoodLead->all_fields_dump = json_encode($this->requestData);
        $this->hoodLead->save();

        return $this;
    }
}
