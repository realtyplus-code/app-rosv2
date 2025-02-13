<?php

namespace App\Http\Requests\Incident;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncidentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->get('type') === 'status') {
            return [
                'status' => 'required|string',
            ];
        }

        $rules = [
            'property_id' => 'required|integer|exists:properties,id',
            'description' => 'required|string|max:1000',
            'report_date' => 'required|date',
            'status_id' => 'required|exists:enum_options,id',
            'incident_type_id' => 'required|exists:enum_options,id',
            'priority_id' => 'required|exists:enum_options,id',
            'cost' => 'nullable|numeric|min:0|max:999999999',
            'payer_id' => 'required|exists:enum_options,id',
            'currency_id' => 'nullable|integer|min:1',
        ];

        $userRole = $this->user() ? $this->user()->roles->toArray()[0]['name'] : null;

        if (in_array($userRole, ['tenant', 'owner', 'ros_client'])) {
            $rules['report_date'] = 'nullable|date';
            $rules['priority_id'] = 'nullable|exists:enum_options,id';
            $rules['payer_id'] = 'nullable|exists:enum_options,id';
            $rules['status_id'] = 'nullable|exists:enum_options,id';
            $rules['currency_id'] = 'nullable|integer|min:1';
            $rules['cost'] = 'nullable|numeric|min:0|max:999999999';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'property_id.exists' => 'The selected property is invalid.',
            'property_id.required' => 'Property is required.',
            'property_id.integer' => 'The property must be an integer.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a valid text.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'report_date.required' => 'The report date field is required.',
            'status_id.required' => 'The status field is required.',
            'incident_type_id.required' => 'The incident type field is required.',
            'priority_id.required' => 'The priority field is required.',
            'cost.numeric' => 'The cost must be a number.',
            'cost.min' => 'The cost must be at least 0.',
            'cost.max' => 'The cost may not be greater than 999999999.',
            'payer_id.required' => 'The payer field is required.',
            'currency_id.integer' => 'The currency must be an integer.',
            'currency_id.min' => 'The currency must be at least 1.',
        ];
    }
}
