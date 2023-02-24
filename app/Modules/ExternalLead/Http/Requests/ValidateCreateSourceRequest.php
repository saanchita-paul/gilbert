<?php

namespace ExternalLead\Http\Requests;

use App\Models\ExternalSource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'email' => ['required', 'email', Rule::notIn(ExternalSource::pluck('email')->toArray())],
            'password' => 'required',
            'source_type' => ['required', Rule::notIn(ExternalSource::pluck('source_type')->toArray())],
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
