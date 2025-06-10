<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required|string|min:8|max:255',
            'new_password' => [
                'required',
                'string',
                'min:8', // Minimum 8 characters
                'max:255',
                'confirmed', // Must match the confirmation field
                'regex:/[0-9]/', // Must contain a number
                'regex:/[!-\/:-@[-`{-~]/', // Must contain a special character
            ],
        ];
    }
}
