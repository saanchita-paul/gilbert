<?php

namespace FastConnect\Services;

use App\Models\ConnectionApplication;

class SubmitWaterLeadToFastConnect
{
    public function get()
    {
        /** @var ConnectionApplication $lead */
        $lead = \App\Models\ConnectionApplication::with([
            'connectionServices',
            'identification',
            'createdBy'
        ])->whereId(65)->first();
        return [
            "agent_code" => "3954V",
            "application_type" => "RESIDENTIAL",
            "locale" => "en",
            "address" => [
                "move_in_address" => [
                    "unit_number" => $lead->unit_number,
                    "street_number" => $lead->street_number,
                    "street_name" => $lead->street_name,
                    "street_type" => $lead->getRoadType(),
                    "suburb" => $lead->city,
                    "state" => $lead->state,
                    "post_code" => $lead->postcode,
//                    "property_type" => "old",
                    "property_ownership" => "rented",
                    "landlord_name" => "string",
                    "landlord_suburb" => "string",
                    "landlord_phone" => "string"
                ]
            ],
            "products" => [
                [
                    "id" => 242,
                    "product_group_id" => 78,
                    "contract_id" => 2977,
                    "requested_date" => "2022-02-01",
                    "marketing" => false
                ]
            ],
            "contact" => [
                "primary" => [
                    "title" => "MR",
                    "first_name" => "Test",
                    "middle_name" => "Test",
                    "last_name" => "Test",
                    "date_of_birth" => "1990-10-18",
                    "email" => "email@example.com",
                    "phone_preference" => "0491570006",
                    "phone_alternate" => "0491570006",
                    "identification" => [
                        [
                            "identification_profile_item_id" => 2,
                            "number" => "EG123456",
                            "issuer_state_id" => 1,
                            "issuer_country_id" => 13,
                            "medicare_color" => "GREEN",
                            "medicare_irn" => 1,
                            "expiry" => "2023-01-01"
                        ]
                    ]
                ],
                "secondary" => [
                    "title" => "MR",
                    "first_name" => "Test",
                    "middle_name" => "Test",
                    "last_name" => "Test",
                    "date_of_birth" => "1990-10-18",
                    "email" => "email@example.com",
                    "phone_preference" => "0491570006",
                    "phone_alternate" => "0491570006",
                    "identification" => [
                        [
                            "identification_profile_item_id" => 2,
                            "number" => "EG123456",
                            "issuer_state_id" => 1,
                            "issuer_country_id" => 13,
                            "medicare_color" => "GREEN",
                            "medicare_irn" => 1,
                            "expiry" => "2023-01-01"
                        ]
                    ]
                ]
            ],

        ];
    }
}
