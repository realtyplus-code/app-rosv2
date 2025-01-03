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
            'owners' => 'required|array',
            'owners.*' => 'required|string',
            'tenants' => 'required|array',
            'tenants.*' => 'required|string',
            'property_type_id' => 'required|string|max:10',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|max:' . config('app.upload_max_filesize'),
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

            'owners.required' => 'Owners array is required.',
            'owners.array' => 'The owners field must be an array.',
            'owners.*.required' => 'Each owner must be provided.',
            'owners.*.string' => 'Each owner must be a string.',

            'tenants.required' => 'Tenants array is required.',
            'tenants.array' => 'The tenants field must be an array.',
            'tenants.*.required' => 'Each tenant must be provided.',
            'tenants.*.string' => 'Each tenant must be a string.',

            'property_type_id.required' => 'Type property is required.',
            'property_type_id.string' => 'The property type ID must be a string.',
            'property_type_id.max' => 'The property type ID may not be greater than 10 characters.',

            'photos.array' => 'The photos field must be an array.',
            'photos.*.image' => 'Each photo must be an image.',
            'photos.*.max' => 'Each photo may not be greater than ' . config('app.upload_max_filesize') . ' kilobytes.',
        ];
    }
}
