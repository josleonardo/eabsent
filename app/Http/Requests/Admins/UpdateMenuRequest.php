<?php

namespace App\Http\Requests\Admins;

use App\Traits\NormalizeFieldTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateMenuRequest extends FormRequest
{
    use NormalizeFieldTrait;

    protected string $menuName = 'menu';

    protected array $fields = ['menu_name', 'url', 'icon'];

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
        $menu = $this->route('menu');
        $id = $menu ? $menu->id : null;

        return [
            'menu_group' => 'required|integer',
            'menu_name' => 'required|string|max:255|lowercase',
            'url' => 'required|string|max:255|lowercase|unique:menus,url,'.$id,
            'platform' => 'required|integer|max:5',
            'order' => 'nullable|integer',
            'icon' => 'nullable|string|max:255|lowercase',
            'active' => 'required|boolean',
        ];
    }
}
