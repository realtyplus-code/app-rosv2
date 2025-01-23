<?php

namespace App\Http\Requests\Insurance;

use Illuminate\Foundation\Http\FormRequest;

class StoreInsuranceRequest extends FormRequest
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
            'insurance_type_id' => 'required|integer',
            'property_id' => 'required|integer',
            'insurance_type_id' => 'required|integer',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'code_number' => 'nullable|string|max:10',
            'code_country' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'policy_number' => 'nullable|string|max:255',
            'renewal_indicator' => 'nullable|string',
            'renewal_months' => 'nullable|integer|min:1',
            'policy_amount' => 'nullable|numeric|min:0',
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
            'coverage_type_id.integer' => 'The coverage type must be an integer.',

            'property_id.required' => 'The property ID is required.',
            'property_id.integer' => 'The property ID must be an integer.',

            'insurance_type_id.required' => 'The insurance type ID is required.',
            'insurance_type_id.integer' => 'The insurance type ID must be an integer.',

            'position.string' => 'The position must be a valid string.',
            'position.max' => 'The position may not be greater than 255 characters.',

            'phone.string' => 'The phone must be a valid string.',
            'phone.max' => 'The phone may not be greater than 20 characters.',

            'code_number.string' => 'The code number must be a valid string.',
            'code_number.max' => 'The code number may not be greater than 10 characters.',

            'code_country.string' => 'The code country must be a valid string.',
            'code_country.max' => 'The code country may not be greater than 10 characters.',

            'country.string' => 'The country must be a valid string.',
            'country.max' => 'The country may not be greater than 255 characters.',

            'policy_number.string' => 'The policy number must be a valid string.',
            'policy_number.max' => 'The policy number may not be greater than 255 characters.',
            'renewal_indicator.string' => 'The renewal number must be a valid string.',
            'renewal_months.integer' => 'The renewal months must be an integer.',
            'renewal_months.min' => 'The renewal months must be at least 1.',
            'policy_amount.numeric' => 'The policy amount must be a number.',
            'policy_amount.min' => 'The policy amount must be at least 0.',
        ];
    }
}
