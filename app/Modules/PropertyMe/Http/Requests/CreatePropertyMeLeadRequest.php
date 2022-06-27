<?php

namespace PropertyMe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePropertyMeLeadRequest extends FormRequest
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
            'leads_data' => 'required|array',
            'office_id' => 'required|integer|exists:offices,id',
        ];
    }
}
