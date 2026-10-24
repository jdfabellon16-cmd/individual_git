<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the data sent to POST /api/v1/auth/register.
 */
class RegisterUserRequest extends FormRequest
{
    /**
     * Anyone may call this endpoint, so authorization always passes.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * The validation rules. On failure Laravel stops here and returns 422.
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            // unique:users,email rejects an email that is already registered
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            // confirmed requires a matching password_confirmation field
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}