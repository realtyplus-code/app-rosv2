<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'phone' => 'nullable|string|min:10|max:15',
            'code_number' => 'nullable|string|max:10',
            'code_country' => 'nullable|string|max:10',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|string|max:255|max:' . config('app.upload_max_filesize'),
            'email' => 'required|string|email|max:255|unique:users,email',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.min' => 'The name must be at least 3 characters.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'phone.string' => 'The phone must be a string.',
            'phone.min' => 'The phone must be at least 10 characters.',
            'phone.max' => 'The phone may not be greater than 15 characters.',
            'code_number.string' => 'The code number must be a string.',
            'code_number.max' => 'The code number may not be greater than 10 characters.',
            'code_country.string' => 'The code country must be a string.',
            'code_country.max' => 'The code country may not be greater than 10 characters.',
            'photos.array' => 'The photos must be an array.',
            'photos.*.string' => 'Each photo must be a string.',
            'photos.*.max' => 'Each photo may not be greater than 255 characters and ' . config('app.upload_max_filesize') . ' kilobytes.',
            'email.required' => 'The email field is required.',
            'email.string' => 'The email must be a string.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.unique' => 'The email has already been taken.',
            'country.string' => 'The country must be a string.',
            'country.max' => 'The country may not be greater than 255 characters.',
            'state.string' => 'The state must be a string.',
            'state.max' => 'The state may not be greater than 255 characters.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than 255 characters.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.max' => 'The password may not be greater than 255 characters.',
        ];
    }
}
