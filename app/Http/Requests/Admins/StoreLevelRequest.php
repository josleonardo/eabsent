<?php

namespace App\Http\Requests\Admins;

use App\Traits\NormalizeFieldTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreLevelRequest extends FormRequest
{
    use NormalizeFieldTrait;

    protected string $menuName = 'level';

    protected string $field = 'level_name';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('access-menu', $this->menuName);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->normalizeFieldToLowercase($this->field);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'level_name' => 'required|string|max:255|lowercase|unique:levels,name,',
            'active' => 'required|boolean',
        ];
    }
}
