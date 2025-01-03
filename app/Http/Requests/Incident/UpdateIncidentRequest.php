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
        return [
            'property_id' => 'sometimes|required|exists:properties,id',
            'description' => 'sometimes|required|string',
            'report_date' => 'sometimes|required|date',
            'status_id' => 'sometimes|required|exists:enum_options,id',
            'incident_type_id' => 'sometimes|required|exists:enum_options,id',
            'priority_id' => 'sometimes|required|exists:enum_options,id',
            'cost' => 'sometimes|required|numeric',
            'payer_id' => 'sometimes|required|exists:enum_options,id',
        ];
    }

    public function messages()
    {
        return [
            'property_id.required' => 'The property field is required.',
            'property_id.exists' => 'The selected property is invalid.',
            'description.required' => 'The description field is required.',
            'report_date.required' => 'The report date field is required.',
            'status_id.required' => 'The status field is required.',
            'incident_type_id.required' => 'The incident type field is required.',
            'priority_id.required' => 'The priority field is required.',
            'cost.required' => 'The cost field is required.',
            'payer_id.required' => 'The payer field is required.',
        ];
    }
}
