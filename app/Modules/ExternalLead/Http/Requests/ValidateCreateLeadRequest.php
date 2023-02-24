<?php

namespace ExternalLead\Http\Requests;

use App\Models\ConnectionApplication;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\ExternalSource;
use Illuminate\Validation\Rule;
use App\Services\Utility\StateMapService;

class ValidateCreateLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $request = request()->all();
        $username = $request['username'];
        $selectedSource = ExternalSource::where('email', $username)->firstOrFail();

        return $this->getRules();
    }

    public function messages()
    {
        $dateFormatMessage = 'The tenancy dob does not match the format yyyy-mm-dd';
        return [
            'tenancy_dob.date_format' => $dateFormatMessage,
            'tenancy_identification_expire_date.date_format' => $dateFormatMessage,
            'tenancy_moving_date.date_format' => $dateFormatMessage,
        ];
    }

    private function getRules()
    {
        $rules = [
            'lead_id' => 'required',
            'tenancy_title' => ['required', Rule::in(['mr', 'ms', 'mrs', 'miss', 'dr'])],
            'tenancy_first_name' => 'required',
            'tenancy_last_name' => 'required',
            'tenancy_email' => 'required|email',
            'tenancy_dob' => 'required|date_format:Y-m-d',
            'tenancy_phone_type' => ['required', Rule::in(['mobile', 'homephone', 'international mobile'])],
            'tenancy_phone_number' => 'required_if:tenancy_phone_type,mobile,international mobile',
            'tenancy_homephone' => 'required_if:tenancy_phone_type,homephone',
            'tenancy_type' => ['required', Rule::in(['renter', 'owner'])],
            'tenancy_property_type' => Rule::in(array_keys(ConnectionApplication::PROPERTY_TYPE_MAPPING)),
            'tenancy_identification_type' => ['required', Rule::in(['medicare', 'passport', 'driver_license'])], // TODO: set options medicare, passport, licence
            'tenancy_identification_number' => 'required',
            'tenancy_identification_state' => 'required_if:tenancy_identification_type,driver_license',
            'tenancy_identification_country' => 'required_if:tenancy_identification_type,passport',
            'tenancy_medicare_card_color' => 'required_if:tenancy_identification_type,medicare',
            'tenancy_medicare_reference_number' => 'required_if:tenancy_identification_type,medicare',
            'tenancy_identification_expire_date' => 'required|date_format:Y-m-d',
            'tenancy_moving_date' => 'required|date_format:Y-m-d',
            'tenancy_street_number' => 'required',
            'tenancy_street_name' => 'required',
            'tenancy_street_type' => 'required',
            'tenancy_city' => 'required_without:tenancy_suburb',
            'tenancy_suburb' => 'required_without:tenancy_city',
            'tenancy_postcode' => 'required',
            'tenancy_state' => ['required', Rule::in(array_keys(StateMapService::SHORT_TO_FULL))],
            'tenancy_country' => ['required', Rule::in(['AUS'])],
            'agent_email' => 'required|email',
            'agency_name' => 'nullable|string',
            'office_name' => 'nullable|string',
        ];

        $ruless = [
            'lead_reference' => 'required',
            'primary_account.title' => ['required', Rule::in(['mr', 'ms', 'mrs', 'miss', 'dr'])],
            'primary_account.first_name' => 'required',
            'primary_account.last_name' => 'required',
            'primary_account.email' => 'required|email',
            'primary_account.dob' => 'required|date_format:Y-m-d',
            'primary_account.phone_type' => ['required', Rule::in(['mobile', 'homephone', 'international mobile'])],
            'primary_account.phone_number' => 'required_if:tenancy_phone_type,mobile,international mobile',
            'primary_account.homephone' => 'required_if:tenancy_phone_type,homephone',

            'primary_account.identification.type' => ['required', Rule::in(['medicare', 'passport', 'driver_license'])], // TODO: set options medicare, passport, licence
            'primary_account.identification.number' => 'required',
            'primary_account.identification.state' => 'required_if:tenancy_identification.type,driver_license',
            'primary_account.identification.country' => 'required_if:identification.type,passport',
            'primary_account.medicare_card_color' => 'required_if:identification.type,medicare',
            'primary_account.medicare_reference_number' => 'required_if:identification.type,medicare',
            'primary_account.identification.expire_date' => 'required|date_format:Y-m-d',

            'connection_details.tenancy_type' => ['required', Rule::in(['renter', 'owner'])],
            'connection_details.property_type' => Rule::in(array_keys(ConnectionApplication::PROPERTY_TYPE_MAPPING)),
            'connection_details.moving_date' => 'required|date_format:Y-m-d',

            'property_address.street_number' => 'required',
            'property_address.street_name' => 'required',
            'property_address.street_type' => 'required',
            'property_address.city' => 'required_without:property_address.suburb',
            'property_address.suburb' => 'required_without:property_address.city',
            'property_address.postcode' => 'required',
            'property_address.state' => ['required', Rule::in(array_keys(StateMapService::SHORT_TO_FULL))],
            'property_address.country' => ['required', Rule::in(['AUS'])],


            'agent.email' => 'required|email',
            'agency_name' => 'nullable|string',
            'office_name' => 'nullable|string',
        ];

        // $billingAddressFields = [
        //     'tenancy_billing_street_number',
        //     'tenancy_billing_street_name',
        //     'tenancy_billing_street_type',
        //     'tenancy_billing_city',
        //     'tenancy_billing_suburb',
        //     'tenancy_billing_postcode',
        //     'tenancy_billing_state',
        //     'tenancy_billing_country',
        // ];

        // foreach ($billingAddressFields as $field) {
        //     $rules[$field] = Rule::requiredIf(function () {
        //         $valid = true;
        //     });
        // }

        return $ruless;
    }
}
