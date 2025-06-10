<?php

namespace App\Http\Requests\Admins;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMenuRequest extends FormRequest
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
        if (! $role || ! $role->id == 1 || ! $role->active || ! optional($role->pivot)->active) {
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
        $menu = $this->route('menu');
        $id = $menu ? $menu->id : null;

        return [
            'menu_group' => 'required|integer',
            'menu_name' => 'required|string|max:255',
            'url' => 'required|string|max:255|unique:menus,url,'.$id,
            'platform' => 'required|integer|max:5',
            'order' => 'nullable|integer',
            'icon' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ];
    }
}
