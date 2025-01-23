<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class StoreProviderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'coverage_area' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'code_number' => 'required|string|max:10',
            'code_country' => 'required|string|max:10',
            'email' => 'required|email|max:255',
            'service_cost' => 'required|numeric|max:99999999',
            'status' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'address.required' => 'The address is required.',
            'coverage_area.required' => 'The coverage area is required.',
            'contact_phone.required' => 'The contact phone is required.',
            'code_number.required' => 'The code number is required.',
            'code_country.required' => 'The country code is required.',
            'email.required' => 'The contact email is required.',
            'service_cost.required' => 'The service cost is required.',
            'service_cost.numeric' => 'The service cost must be a number.',
            'service_cost.max' => 'The service cost may not be greater than 99999999.',
            'status.required' => 'The status is required.',
            'status.boolean' => 'The status must be true or false.',
        ];
    }
}
