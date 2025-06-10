<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAttendanceRequest extends FormRequest
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
        if (! $role || ! in_array($role->id, [1, 2, 3]) || ! $role->active || ! optional($role->pivot)->active) {
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
        return [
            'actual_in' => 'nullable|date_format:H:i',
            'actual_out' => 'nullable|date_format:H:i',
            'status' => 'required|integer|max:20',
        ];
    }
}
