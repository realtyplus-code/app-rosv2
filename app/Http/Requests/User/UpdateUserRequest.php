<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->input('id');

        return [
            'name' => 'required|string|min:3|max:255',
            'user' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('users', 'user')->ignore($userId),
                Rule::unique('providers', 'user')->ignore($userId),
            ],
            'phone' => 'nullable|string|min:10|max:15',
            'code_number' => 'nullable|string|max:10',
            'code_country' => 'nullable|string|max:10',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
                Rule::unique('providers', 'email')->ignore($userId),
            ],
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|max:255',
            'language_id' => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.min' => 'The name must be at least 3 characters.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'user.required' => 'The user field is required.',
            'user.string' => 'The user must be a string.',
            'user.min' => 'The user must be at least 3 characters.',
            'user.max' => 'The user may not be greater than 255 characters.',
            'user.unique' => 'The user has already been taken in users or proveedores.',
            'phone.string' => 'The phone must be a string.',
            'phone.min' => 'The phone must be at least 10 characters.',
            'phone.max' => 'The phone may not be greater than 15 characters.',
            'code_number.string' => 'The code number must be a string.',
            'code_number.max' => 'The code number may not be greater than 10 characters.',
            'code_country.string' => 'The code country must be a string.',
            'code_country.max' => 'The code country may not be greater than 10 characters.',
            'email.required' => 'The email field is required.',
            'email.string' => 'The email must be a string.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.unique' => 'The email has already been taken in users or proveedores.',
            'country.string' => 'The country must be a string.',
            'country.max' => 'The country may not be greater than 255 characters.',
            'state.string' => 'The state must be a string.',
            'state.max' => 'The state may not be greater than 255 characters.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than 255 characters.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.max' => 'The password may not be greater than 255 characters.',
            'language_id.integer' => 'The language must be an integer.',
            'language_id.min' => 'The language must be at least 1.',
        ];
    }
}
