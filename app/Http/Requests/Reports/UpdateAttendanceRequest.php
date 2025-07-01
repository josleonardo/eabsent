<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateAttendanceRequest extends FormRequest
{
    protected string $menuName = 'attendance';

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
        return [
            'actual_in' => 'nullable|date_format:H:i',
            'actual_out' => 'nullable|date_format:H:i',
            'status' => 'required|integer|max:20',
        ];
    }
}
