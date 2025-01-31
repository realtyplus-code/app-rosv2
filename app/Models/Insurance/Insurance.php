<?php

namespace App\Models\Insurance;

use Carbon\Carbon;
use App\Models\Property\Property;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Insurance extends Model
{
    protected $fillable = [
        'insurance_company',
        'start_date',
        'end_date',
        'contact_person',
        'contact_email',
        'insurance_type_id',
        'position',
        'phone',
        'code_number',
        'code_country',
        'country',
        'policy_number',
        'renewal_indicator',
        'renewal_months',
        'policy_amount'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'renewal_indicator' => 'boolean',
        'policy_amount' => 'decimal:2',
    ];

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }


    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }


    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function setRenewalIndicatorAttribute($value)
    {
        $this->attributes['renewal_indicator'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
