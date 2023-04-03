<?php

namespace ExternalLead\Services;

use ExternalLead\Models\ExternalLeadApiLog;

class SaveRawData
{
    /** save all fields to dump
     * @param int externalLeadId
     * @param array data
     * @return ExternalLeadApiLog
     */
    public static function dump(int $externalSourceId, array $data): ExternalLeadApiLog
    {
        $newDump = new ExternalLeadApiLog();
        $newDump->lead_id = $data['lead_reference'] ?? null;
        $newDump->all_fields_dump = json_encode($data);
        $newDump->external_source_id = $externalSourceId;
//        $newDump->agent_name = !empty($data['agent_firstname']) ? ($data['agent_firstname'] . ($data['agent_lastname'] ? " " . $data['agent_lastname'] : '')) : null;
        $newDump->agency_name = $data['agency']['agency_name'] ?? null;
        $newDump->agent_email = $data['agency']['agent_email'] ?? null;
        $newDump->save();

        return $newDump;
    }
}
