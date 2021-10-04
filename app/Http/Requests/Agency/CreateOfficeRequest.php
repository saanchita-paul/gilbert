<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class CreateOfficeRequest extends FormRequest
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
            'office.address'=>'required|string',
            'office.name'=>'required|string',
            'office.phone'=>'required|string',
            'office.email'=>'required|email',
            'office.abn'=>'nullable|string',

            'agent.first_name'=>'required|string',
            'agent.last_name'=>'required|string',
            'agent.email'=>'required|email',
            'agent.f_id_12'=>'nullable|string',
            'agent.phone'=>'nullable|string',
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
