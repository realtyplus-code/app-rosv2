<?php

namespace App\Http\Requests\IncidentAction;

use Illuminate\Foundation\Http\FormRequest;

class ValidatePhotoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('app.upload_max_filesize'),
        ];
    }

    public function messages()
    {
        return [
            'photo.required' => 'The photo is required.',
            'photo.image' => 'The file must be an image.',
            'photo.mimes' => 'The image must be of type jpeg, png, jpg, or gif.',
            'photo.max' => 'The image must not exceed ' . config('app.upload_max_filesize') . ' kilobytes.',
        ];
    }
}
