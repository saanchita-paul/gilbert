<?php

namespace ExternalLead\Http\Requests;

use App\Models\ConnectionApplication;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\ExternalSource;
use Illuminate\Validation\Rule;
use App\Services\Utility\StateMapService;
use App\Models\ConnectionApplicationSecondaryACC as AuthorizedPerson;

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
        $dobDateFormatMessage = 'The tenancy dob does not match the format yyyy-mm-dd.';
        $expireDateFormatMessage = 'The identification expiry date format does not match the format yyyy-mm-dd.';
        $movingDateFormatMessage = 'The connection details moving date does not match the format yyyy-mm-dd.';
        return [
            'primary_account.dob.date_format' => $dobDateFormatMessage,
            'primary_account.identification.expire_date.date_format' => $expireDateFormatMessage,
            'connection_details.moving_date.date_format' => $movingDateFormatMessage,
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
            'primary_account.phone_number' => 'required_if:primary_account.phone_type,mobile,international_mobile', // TODO: handle homephone

            'utility_services' => 'required|array',
            'utility_services.*' =>  Rule::in("gas", 'power'),

            'primary_account.identification.type' => ['required', Rule::in(['medicare', 'passport', 'driver_license'])],
            'primary_account.identification.number' => 'required',
            'primary_account.identification.state' => 'required_if:tenancy_identification.type,driver_license',
            'primary_account.identification.country' => 'required_if:identification.type,passport',
            'primary_account.identification.medicare_card_color' => 'required_if:identification.type,medicare',
            'primary_account.identification.medicare_reference_number' => 'required_if:identification.type,medicare',
            'primary_account.identification.expire_date' => 'required|date_format:Y-m-d',

            'secondary_account.title' => ['required_with:secondary_account', Rule::in(['mr', 'ms', 'mrs', 'miss', 'dr'])],
            'secondary_account.first_name' => 'required_with:secondary_account',
            'secondary_account.last_name' => 'required_with:secondary_account',
            'secondary_account.email' => 'required_with:secondary_account',
            'secondary_account.dob' => 'required_with:secondary_account|date_format:Y-m-d',
            'secondary_account.phone_number' => 'required_with:secondary_account',
            'secondary_account.permission_type' => ['required_with:secondary_account', Rule::in(array_keys(AuthorizedPerson::ROLE_TYPE_MAPPER))],

            'secondary_account.identification.type' => ['required_with:secondary_account.identification', Rule::in(['medicare', 'passport', 'driver_license'])],
            'secondary_account.identification.number' => 'required_with:secondary_account.identification',
            'secondary_account.identification.state' => 'required_if:secondary_account.identification.type,driver_license',
            'secondary_account.identification.country' => 'required_if:secondary_account.identification.type,passport',
            'secondary_account.identification.medicare_card_color' => 'required_if:secondary_account.identification.type,medicare',
            'secondary_account.identification.medicare_reference_number' => 'required_if:secondary_account.identification.type,medicare',
            'secondary_account.identification.expire_date' => 'required_with:secondary_account.identification|date_format:Y-m-d',

            'connection_details.tenancy_type' => ['required', Rule::in(['renter', 'home_owner'])],
            'connection_details.property_type' => Rule::in(array_keys(ConnectionApplication::PROPERTY_TYPE_MAPPING)),
            'connection_details.moving_date' => 'required|date_format:Y-m-d',
            "connection_details.has_life_support" => "nullable|boolean",
            "connection_details.is_renovation_on" => "nullable|boolean",
            'connection_details.is_email_billing' => 'nullable|boolean',
            "connection_details.has_solar" => "nullable|boolean",
            "connection_details.nmi" => "nullable|string",
            "connection_details.mirn" => "nullable|string",

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
            'billing_address.street_number' => 'required_with:billing_address',
            'billing_address.street_name' => 'required_with:billing_address',
            'billing_address.street_type' => 'required_with:billing_address',
            'billing_address.city' => Rule::requiredIf(function () {
                return request()->exists('billing_address') && !request()->exists('billing_address.suburb');
            }),
            'billing_address.suburb' => Rule::requiredIf(function () {
                return request()->exists('billing_address') && !request()->exists('billing_address.city');
            }),
            'billing_address.postcode' => 'required_with:billing_address',
            'billing_address.state' => ['required_with:billing_address', Rule::in(array_keys(StateMapService::SHORT_TO_FULL))],
            // billing address country?

            'agency.agent_email' => 'required|email',
            'agency.name' => 'nullable|string',
            'agency.office_name' => 'nullable|string',
        ];
    }
}
