<?php

namespace ExternalLead\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCreateSourceRequest extends FormRequest
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
        return [
            'email' => 'required|email',
            'password' => 'required',
            'source_type' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
