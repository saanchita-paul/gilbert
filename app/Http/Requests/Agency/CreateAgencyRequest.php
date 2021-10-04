<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class CreateAgencyRequest extends FormRequest
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
            'type'=>'required|integer',
            'name'=>'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Agency type is required!',
            'type.integer' => 'Agency type is Numeric!',
            'name.required' => 'Agency Name is required!',
            'name.string' => 'Agency Name is String'
        ];
    }
}
