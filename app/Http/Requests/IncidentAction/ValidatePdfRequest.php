<?php

namespace App\Http\Requests\IncidentAction;

use Illuminate\Foundation\Http\FormRequest;

class ValidatePdfRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pdfs' => 'required|array',
            'pdfs.*' => 'file|mimes:pdf|max:' . config('app.upload_max_filesize'),
        ];
    }

    public function messages()
    {
        return [
            'pdfs.required' => 'At least one PDF is required.',
            'pdfs.array' => 'The PDFs must be an array.',
            'pdfs.*.file' => 'Each PDF must be a file.',
            'pdfs.*.mimes' => 'Each PDF must be of type pdf.',
            'pdfs.*.max' => 'Each PDF must not exceed ' . config('app.upload_max_filesize') . ' kilobytes.',
        ];
    }
}
