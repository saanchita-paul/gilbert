<?php

namespace ExternalLead\Services;

use ExternalLead\Models\TApp;

class SaveRawData
{
    /** save all fields to dump
     * @param void
     * @return void
     */
    public static function dump(int $externalLeadId, array $data)
    {
        $newDump = new TApp();
        $newDump->lead_id = $data['tapp_lead_id'] ?? null;
        $newDump->all_fields_dump = json_encode($data);
        $newDump->external_lead_id = $externalLeadId;
        $newDump->agent_name = !empty($data['agent_firstname']) ? $data['agent_firstname'] . ($data['agent_lastname'] ?? '') : null;
        $newDump->agency_name = $data['agency_name'] ?? null;
        $newDump->agent_email = $data['agent_email'] ?? null;
        $newDump->save();

        return $newDump;
    }
}
