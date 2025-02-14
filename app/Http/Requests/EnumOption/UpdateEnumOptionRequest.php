<?php

namespace App\Http\Requests\EnumOption;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEnumOptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                function ($attribute, $value, $fail) {
                    $parentId = $this->input('parent_id');
                    $id = $this->route('id'); // Obtén el ID desde la ruta
                    if (DB::table('enum_options')
                        ->where('parent_id', $parentId)
                        ->where('name', $value)
                        ->where('id', '!=', $id) // Excluir el registro actual
                        ->exists()
                    ) {
                        $fail("The name '{$value}' already exists in the group.");
                    }
                },
            ],
            'parent_id' => 'required|exists:enum_options,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'parent_id.required' => 'The group (parent_id) field is required.',
            'parent_id.exists' => 'The selected group is invalid.',
        ];
    }
}
