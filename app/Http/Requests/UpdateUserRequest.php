<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if (! $user || ! $user->active) {
            return false;
        }

        $role = $user->roles->first();
        if (! $role || ! in_array($role->id, [1, 2]) || ! $role->active || ! optional($role->pivot)->active) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('user');

        return [
            'email' => 'required|email|unique:users,email,'.$id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,'.$id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nik' => 'required|digits:16|unique:user_profiles,nik,'.$id.',user_id',
            'nuptk' => 'nullable|digits:16|unique:user_profiles,nuptk,'.$id.',user_id',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'nullable|integer|exists:roles,id',
            'level' => 'nullable|integer|exists:levels,id',
            'schedule' => [
                'nullable',
                'regex:/^(\d+(,\d+)*)?$/', // Comma-separated list of integers
            ],
            'employment_start' => 'required|date',
            'employment_end' => 'nullable|date',
            'active' => 'required|boolean',
        ];
    }
}
