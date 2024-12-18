<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|string',
            'owners' => 'required|array|min:1',
            'owners.*' => 'required|string',
            'tenants' => 'required|array|min:1',
            'tenants.*' => 'required|string',
            'property_type_id' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Type name is required.',
            'name.string' => 'The name must be a string.',

            'address.required' => 'Type address is required.',
            'address.string' => 'The address must be a string.',

            'status.required' => 'Type status is required.',
            'status.string' => 'The status must be a string.',

            'owners.required' => 'Owners array is required.',
            'owners.array' => 'The owners field must be an array.',
            'owners.min' => 'At least one owner is required.',
            'owners.*.required' => 'Each owner must be provided.',
            'owners.*.string' => 'Each owner must be a string.',

            'tenants.required' => 'Tenants array is required.',
            'tenants.array' => 'The tenants field must be an array.',
            'tenants.min' => 'At least one tenant is required.',
            'tenants.*.required' => 'Each tenant must be provided.',
            'tenants.*.string' => 'Each tenant must be a string.',

            'property_type_id.required' => 'Type property is required.',
            'property_type_id.string' => 'The property type ID must be a string.',
        ];
    }
}
