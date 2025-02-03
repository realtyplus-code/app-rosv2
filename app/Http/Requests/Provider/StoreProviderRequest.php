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
            'user' => 'required|string|min:3|max:255|unique:users,user|unique:providers,user',
            'address' => 'required|string|max:255',
            'coverage_area' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'code_number' => 'required|string|max:10',
            'code_country' => 'required|string|max:10',
            'email' => 'required|string|email|max:255|unique:users,email|unique:providers,email',
            'service_cost' => 'required|numeric|max:99999999',
            'password' => 'required|string|max:255',
            'language_id' => 'required|integer',
            'website' => 'required|url|max:255',
            'status' => 'required',
            'providers' => 'required|array|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'user.required' => 'The user is required.',
            'user.min' => 'The user must be at least 3 characters.',
            'user.unique' => 'The user has already been taken.',
            'address.required' => 'The address is required.',
            'coverage_area.required' => 'The coverage area is required.',
            'contact_phone.required' => 'The contact phone is required.',
            'contact_phone.max' => 'The contact phone may not be greater than 20 characters.',
            'code_number.required' => 'The code number is required.',
            'code_number.max' => 'The code number may not be greater than 10 characters.',
            'code_country.required' => 'The country code is required.',
            'code_country.max' => 'The country code may not be greater than 10 characters.',
            'email.required' => 'The contact email is required.',
            'email.email' => 'The contact email must be a valid email address.',
            'email.unique' => 'The contact email has already been taken.',
            'service_cost.required' => 'The service cost is required.',
            'service_cost.numeric' => 'The service cost must be a number.',
            'service_cost.max' => 'The service cost may not be greater than 99999999.',
            'password.required' => 'The password is required.',
            'language_id.required' => 'The language is required.',
            'language_id.integer' => 'The language must be an integer.',
            'website.required' => 'The website is required.',
            'website.url' => 'The website format is not valid.',
            'website.max' => 'The website may not be greater than 255 characters.',
            'status.required' => 'The status is required.',
            'providers.required' => 'At least one service is required.',
            'providers.array' => 'The providers must be an array.',
            'providers.min' => 'At least one provider is required.',
        ];
    }
}
