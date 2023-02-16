<?php

namespace ExternalLead\Services;

use ExternalLead\Models\TApp;

class SaveRawData
{
    /** save all fields to dump
     * @param int externalLeadId
     * @param array data
     * @return TApp
     */
    public static function dump(int $externalSourceId, array $data): TApp
    {
        $newDump = new TApp();
        $newDump->lead_id = $data['tapp_lead_id'] ?? null;
        $newDump->all_fields_dump = json_encode($data);
        $newDump->external_source_id = $externalSourceId;
        $newDump->agent_name = !empty($data['agent_firstname']) ? $data['agent_firstname'] . ($data['agent_lastname'] ?? '') : null;
        $newDump->agency_name = $data['agency_name'] ?? null;
        $newDump->agent_email = $data['agent_email'] ?? null;
        $newDump->save();

        return $newDump;
    }
}
