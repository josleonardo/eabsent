<?php

namespace App\Http\Requests\Admins;

use App\Traits\MenuAuthorizationTrait;
use App\Traits\NormalizeFieldTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAppSettingRequest extends FormRequest
{
    use MenuAuthorizationTrait, NormalizeFieldTrait;

    protected string $menuName = 'app setting';

    protected array $fields = ['setting_name', 'key', 'value_1', 'value_2'];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkMenuAuthorization($this->menuName);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        foreach ($this->fields as $field) {
            $this->normalizeFieldToLowercase($field);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $appSetting = $this->route('appSetting');
        $id = $appSetting ? $appSetting->id : null;

        return [
            'setting_name' => 'required|string|max:255|lowercase|unique:app_settings,name,'.$id,
            'key' => 'nullable|string|max:255|lowercase|unique:app_settings,key,'.$id,
            'value_1' => 'nullable|string|max:255|lowercase',
            'value_2' => 'nullable|string|max:255|lowercase',
            'active' => 'required|boolean',
        ];
    }
}
