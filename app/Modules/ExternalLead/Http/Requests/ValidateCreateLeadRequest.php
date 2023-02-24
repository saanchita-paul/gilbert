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
            'tenancy_property_type' => Rule::in(ConnectionApplication::PROPERTY_TYPE_MAPPING),
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
            'agency_name' => 'required',
            'office_name' => 'required',
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

        return $rules;
    }
}
