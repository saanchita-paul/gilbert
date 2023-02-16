<?php

namespace ExternalLead\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC as AuthorisedPerson;

class CreateAuthorizedPersonService
{
    public function save(ConnectionApplication $app, array $data)
    {
        $authorisedPerson = new AuthorisedPerson();
        $authorisedPerson->connection_application_id = $app->id;
        $authorisedPerson->title = $data['tenancy_secondary_title'] ?
            ucwords($data['tenancy_secondary_title']) : null;
        $authorisedPerson->first_name = $data['tenancy_secondary_first_name'] ?? null;
        $authorisedPerson->last_name = $data['tenancy_secondary_last_name'] ?? null;
        $authorisedPerson->middle_name = $data['tenancy_secondary_middle_name'] ?? null;
        $authorisedPerson->email = $data['tenancy_secondary_email'] ?? null;
        $authorisedPerson->phone = $data['tenancy_secondary_phone_number'] ?? null;
        $authorisedPerson->role = $data['tenancy_secondary_permission_type'] ?
            AuthorisedPerson::ROLE_TYPE_MAPPER[$data['tenancy_secondary_permission_type']] : null;
        $authorisedPerson->dob = $data['tenancy_secondary_dob'] ?? null;
        $authorisedPerson->save();

        return $authorisedPerson;
    }
}
