<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'=> 'required|string',
            'last_name'=> 'required|string',
            'email'=> 'required|string|email',
            'phone'=> 'required|string',
            'tenancy_type'=> 'nullable|string',
            'moving_date'=> 'required',
            'address_unit'=> 'nullable|string',
            'street_address'=> 'required|string',
            'city'=> 'required|string',
            'postcode'=> 'required|string',
            'state'=> 'required|string',
            'additional_instruction'=> 'nullable|string',
            'is_email_billing'=> 'nullable|integer',
        ];
    }
}
