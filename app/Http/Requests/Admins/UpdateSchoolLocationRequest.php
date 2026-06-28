<?php

namespace App\Http\Requests\Admins;

use App\Traits\NormalizeFieldTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateSchoolLocationRequest extends FormRequest
{
    use NormalizeFieldTrait;

    protected string $menuName = 'school location';

    protected array $fields = ['school_location_name', 'key'];

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
        $schoolLocation = $this->route('school_location');
        $id = $schoolLocation ? $schoolLocation->id : null;

        return [
            'school_location_name' => 'required|string|max:255|lowercase|unique:school_locations,name,'.$id,
            'key' => 'nullable|string|max:255|lowercase|unique:school_locations,key,'.$id,
            'latitude' => 'nullable|decimal:0,8',
            'longitude' => 'nullable|decimal:0,8',
            'radius' => 'nullable|integer',
            'active' => 'required|boolean',
        ];
    }
}
