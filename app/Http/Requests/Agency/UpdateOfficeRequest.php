<?php


namespace App\Http\Requests\Agency;


use Illuminate\Foundation\Http\FormRequest;

class UpdateOfficeRequest extends FormRequest
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
            'office.id'=>'required',
            'office.address'=>'required|string',
            'office.name'=>'required|string',
            'office.phone'=>'required|string',
            'office.email'=>'required|email',
            'office.abn'=>'nullable|string',

            // 'agent.id'=>'required',
            'agent.phone'=>'nullable|string',
        ];
    }

}
