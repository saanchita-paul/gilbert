<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class CreateIndependentAgencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'agency.type' => 'required|integer',
            'agency.name' => 'required|string',

            'office.address' => 'required|string',
            'office.name' => 'required|string',
            'office.phone' => 'required|string',
            'office.email' => 'required|email',
            'office.abn' => 'nullable|string',

            'agent.first_name' => 'required|string',
            'agent.last_name' => 'required|string',
            'agent.email' => 'required|email',
            'agent.f_id_12' => 'nullable|string',
            'agent.phone' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
