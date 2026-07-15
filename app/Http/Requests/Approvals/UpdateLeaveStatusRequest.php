<?php

namespace App\Http\Requests\Approvals;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateLeaveStatusRequest extends FormRequest
{
    protected string $menuName = 'leave';

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
            'action' => 'required|in:approve,reject',
        ];
    }
}
