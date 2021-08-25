<?php

namespace App\Http\Requests\Agency;

use App\Models\User;
use App\Services\RolePermission;
use Illuminate\Foundation\Http\FormRequest;

class CreateHoodUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = auth()->user();
        return $user->hasAnyRole(RolePermission::ROLE_HOOD_ADMIN);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "first_name" => "required|string",
            "last_name" => "required|string",
            "phone" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required|string"
        ];
    }
}
