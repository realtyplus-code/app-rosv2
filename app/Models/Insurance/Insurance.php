<?php

namespace App\Models\Insurance;

use Carbon\Carbon;
use App\Models\Property\Property;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = [
        'insurance_company',
        'start_date',
        'end_date',
        'contact_person',
        'contact_email',
        'property_id',
        'insurance_type_id',
        'position',
        'phone',
        'code_number',
        'code_country',
        'country',
        'policy_number',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Relación: Seguro - Propiedad (property_id)
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

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
}
