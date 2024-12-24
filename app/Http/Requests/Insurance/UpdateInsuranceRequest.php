<?php

namespace App\Http\Requests\Insurance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInsuranceRequest extends FormRequest
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
            'insurance_company' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'coverage_type_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'insurance_company.required' => 'The insurance company field is required.',
            'insurance_company.string' => 'The insurance company must be a valid string.',
            'insurance_company.max' => 'The insurance company may not be greater than 255 characters.',

            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date must be a valid date.',

            'end_date.required' => 'The end date is required.',
            'end_date.date' => 'The end date must be a valid date.',

            'contact_person.required' => 'The contact person field is required.',
            'contact_person.string' => 'The contact person must be a valid string.',
            'contact_person.max' => 'The contact person may not be greater than 255 characters.',

            'contact_email.required' => 'The contact email is required.',
            'contact_email.email' => 'The contact email must be a valid email address.',
            'contact_email.max' => 'The contact email may not be greater than 255 characters.',

            'coverage_type_id.required' => 'The coverage type is required.',
            'coverage_type_id.integer' => 'The coverage type must be an integer.'
        ];
    }
}
