<?php

namespace ExternalLead\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ExternalSource;

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

        return [
            'tenancy_title' => 'required', // TODO: set options mr, ms, mrs, etc
            'tenancy_first_name' => 'required',
            'tenancy_last_name' => 'required',
            'tenancy_email' => 'required|email',
            'tenancy_dob' => 'required|date_format:Y-m-d',
            'tenancy_phone_number' => 'required_without:tenancy_homephone',
            'tenancy_homephone' => 'required_without:tenancy_phone_number',
            'tenancy_type' => 'required', // TODO: set options renter, homeowner
            'tenancy_identification_type' => 'required', // TODO: set options medicare, passport, licence
            'tenancy_identification_number' => 'required',
            'tenancy_identification_state' => 'required_if:tenancy_identification_type,state',
            'tenancy_identification_country' => 'required_if:tenancy_identification_type,passport',
            'tenancy_medicare_card_color' => 'required_if:tenancy_identification_type,medicare',
            'tenancy_medicare_reference_number' => 'required_if:tenancy_identification_type,medicare',
            'tenancy_identification_expire_date' => 'required|date_format:Y-m-d',
        ];
    }
}
