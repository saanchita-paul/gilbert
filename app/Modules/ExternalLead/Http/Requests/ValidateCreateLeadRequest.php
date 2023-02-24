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

    private function getRules(): array
    {
        return [
            'lead_reference' => 'required|string|unique:t_app,lead_id',
            'primary_account.title' => ['required', Rule::in(['mr', 'ms', 'mrs', 'miss', 'dr'])],
            'primary_account.first_name' => 'required|string',
            'primary_account.middle_name' => 'nullable|string',
            'primary_account.last_name' => 'required|string',
            'primary_account.email' => 'required|email',
            'primary_account.dob' => 'required|date_format:Y-m-d',
            'primary_account.phone_type' => ['required', Rule::in(['mobile', 'homephone', 'international_mobile'])],
            'primary_account.phone_number' => 'required_if:primary_account.phone_type,mobile,international_mobile',
            'primary_account.homephone' => 'required_if:primary_account.homephone,homephone',

            'utility_services.0' =>  'required',
            'utility_services.*' =>  Rule::in("gas", 'power'),

            'primary_account.identification.type' => ['required', Rule::in(['medicare', 'passport', 'driver_license'])],
            // TODO: set options medicare, passport, licence
            'primary_account.identification.number' => 'required',
            'primary_account.identification.state' => 'required_if:tenancy_identification.type,driver_license',
            'primary_account.identification.country' => 'required_if:identification.type,passport',
            'primary_account.medicare_card_color' => 'required_if:identification.type,medicare',
            'primary_account.medicare_reference_number' => 'required_if:identification.type,medicare',
            'primary_account.identification.expire_date' => 'required|date_format:Y-m-d',

            'secondary_account.title' => ['required_with:secondary_account', Rule::in(['mr', 'ms', 'mrs', 'miss', 'dr'])],
            'secondary_account.first_name' => 'required_with:secondary_account',
            'secondary_account.last_name' => 'required_with:secondary_account',
            'secondary_account.email' => 'required_with:secondary_account',
            'secondary_account.dob' => 'required_with:secondary_account|date_format:Y-m-d',
            'secondary_account.phone_number' => 'required_with:secondary_account',
            'secondary_account.permission_type' => 'required_with:secondary_account',

            'connection_details.tenancy_type' => ['required', Rule::in(['renter', 'home_owner'])],
            'connection_details.property_type' => Rule::in(array_keys(ConnectionApplication::PROPERTY_TYPE_MAPPING)),
            'connection_details.is_email_billing' => ['required', 'boolean'],
            'connection_details.moving_date' => 'required|date_format:Y-m-d',
            "additional_instruction" => "Additional instruction here",
            "has_life_support" => "nullable|boolean",
            "is_renovation_on" => "nullable|boolean",
            "has_solar" => "nullable|boolean",
            "nmi" => "nullable|string",
            "mirn" => "nullable|string",

            'property_address.unit_number' => 'nullable',
            'property_address.street_number' => 'required',
            'property_address.street_name' => 'required',
            'property_address.street_type' => 'required',
            'property_address.city' => 'required_without:property_address.suburb',
            'property_address.suburb' => 'required_without:property_address.city',
            'property_address.postcode' => 'required',
            'property_address.state' => ['required', Rule::in(array_keys(StateMapService::SHORT_TO_FULL))],
            'property_address.country' => ['required', Rule::in(['AUS'])],

            'billing_address.unit_number' => 'nullable',
            'billing_address.street_number' => 'required',
            'billing_address.street_name' => 'required',
            'billing_address.street_type' => 'required',
            'billing_address.city' => 'required_without:property_address.suburb',
            'billing_address.suburb' => 'required_without:property_address.city',
            'billing_address.postcode' => 'required',
            'billing_address.state' => ['required', Rule::in(array_keys(StateMapService::SHORT_TO_FULL))],


            'agency.agent_email' => 'required|email',
            'agency.name' => 'nullable|string',
            'agency.office_name' => 'nullable|string',
        ];
    }
}
