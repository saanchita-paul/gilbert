<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppClosingReasonRequest extends FormRequest
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
            'value'=>'required|unique:app_close_reasons,value,'.$this->id,
        ];
    }

    public function messages()
    {
        return [
            'value.required' => 'Reason value is required!',
            'value.unique' => 'The value already Exists!'
        ];
    }
}
