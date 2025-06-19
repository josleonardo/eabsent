<?php

namespace App\Http\Requests\Admins;

use App\Traits\MenuAuthorizationTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserScheduleRequest extends FormRequest
{
    use MenuAuthorizationTrait;

    protected string $menuName = 'user schedule';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkMenuAuthorization($this->menuName);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user' => 'required|exists:users,id',
            'schedule' => 'required|exists:schedules,id',
            'active' => 'required|boolean',
        ];
    }
}
