<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255',
            'address' => 'required|string|min:5|max:255',
            'status' => 'required|string|min:1|max:50',
            'owners' => 'nullable|array',
            'owners.*' => 'nullable|string',
            'tenants' => 'nullable|array',
            'tenants.*' => 'nullable|string',
            'property_type_id' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'expected_end_date_ros' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Type name is required.',
            'name.string' => 'The name must be a string.',
            'name.min' => 'The name must be at least 3 characters.',
            'name.max' => 'The name may not be greater than 255 characters.',

            'address.required' => 'Type address is required.',
            'address.string' => 'The address must be a string.',
            'address.min' => 'The address must be at least 5 characters.',
            'address.max' => 'The address may not be greater than 255 characters.',

            'status.required' => 'Type status is required.',
            'status.string' => 'The status must be a string.',
            'status.min' => 'The status must be at least 1 characters.',
            'status.max' => 'The status may not be greater than 50 characters.',

            'owners.array' => 'The owners field must be an array.',
            'owners.*.string' => 'Each owner must be a string.',

            'tenants.required' => 'Tenants array is required.',
            'tenants.array' => 'The tenants field must be an array.',
            'tenants.*.required' => 'Each tenant must be provided.',
            'tenants.*.string' => 'Each tenant must be a string.',

            'property_type_id.required' => 'Type property is required.',
            'property_type_id.string' => 'The property type ID must be a string.',
            'property_type_id.max' => 'The property type ID may not be greater than 10 characters.',

            'country.required' => 'Country is required.',
            'country.string' => 'The country must be a string.',
            'country.max' => 'The country may not be greater than 255 characters.',

            'state.required' => 'State is required.',
            'state.string' => 'The state must be a string.',
            'state.max' => 'The state may not be greater than 255 characters.',

            'city.required' => 'City is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than 255 characters.',

            'expected_end_date_ros.date' => 'The expected end date must be a valid date.',
        ];
    }
}
