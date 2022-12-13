<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ApplicationServiceStatusRequest extends FormRequest
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
            'type' => 'bail|required|string|in:application,service',
            'display_text' => 'bail|required|string',
            'display_text_alias' => 'nullable|string',
            'status_value' => 'bail|required|string',
        ];
    }

    public function validated(): array
    {
        // Make status value snake case
        if ($this->has('status_value')) {
            return array_merge(parent::validated(), ['status_value' => Str::snake($this->input('status_value'))]);
        }

        return parent::validated();
    }
}
