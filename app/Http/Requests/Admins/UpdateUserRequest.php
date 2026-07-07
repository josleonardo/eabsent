<?php

namespace App\Http\Requests\Admins;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    protected string $menuName = 'user';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('access-menu', $this->menuName);
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
            'username' => 'nullable|string|max:255|unique:users,username,'.$id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nik' => 'required|digits:16|unique:user_profiles,nik,'.$id.',user_id',
            'nuptk' => 'nullable|digits:16|unique:user_profiles,nuptk,'.$id.',user_id',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'nullable|integer|exists:roles,id',
            'level' => 'nullable|integer|exists:levels,id',
            'school_location_id' => 'nullable|integer|exists:school_locations,id',
            'schedule_group_id' => 'nullable|integer|exists:schedule_groups,id',
            'employment_start' => 'required|date',
            'employment_end' => 'nullable|date',
            'active' => 'required|boolean',
        ];
    }
}
