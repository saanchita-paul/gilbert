<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationServiceStatusChangeRequest extends FormRequest
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
            'application_status' => 'bail|nullable|integer',
            'power_status' => 'bail|nullable|integer',
            'gas_status' => 'bail|nullable|integer',
            'water_status' => 'bail|nullable|integer',
            'internet_status' => 'bail|nullable|integer',
            'status_reason' => 'bail|nullable|string',
        ];
    }
}
