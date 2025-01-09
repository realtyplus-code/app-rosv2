<?php

namespace App\Http\Requests\IncidentAction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncidentActionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'action_date' => 'required|string',
            'responsible_user_id' => 'required|integer',
            'action_description' => 'required|string|max:1000',
            'action_cost' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'action_date.required' => 'The action date is required.',
            'action_date.string' => 'The action date must be a valid text.',

            'responsible_user_id.required' => 'The responsible user ID is required.',
            'responsible_user_id.integer' => 'The responsible user ID must be an integer.',

            'action_description.required' => 'The action description is required.',
            'action_description.string' => 'The action description must be valid text.',
            'action_description.max' => 'The description may not be greater than 1000 characters.',

            'action_cost.required' => 'The action cost is required.',
            'action_cost.numeric' => 'The action cost must be a valid number.',
        ];
    }
}
