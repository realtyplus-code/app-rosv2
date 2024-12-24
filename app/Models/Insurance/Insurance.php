<?php

namespace App\Models\Insurance;

use Carbon\Carbon;
use App\Models\Property\Property;
use Illuminate\Database\Eloquent\Model;
use App\Models\Configuration\EnumOption;

class Insurance extends Model
{
    protected $fillable = [
        'insurance_company',
        'start_date',
        'end_date',
        'contact_person',
        'contact_email',
        'property_id',
        'coverage_type_id',
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

    /**
     * Relación: Seguro - Tipo de cobertura (coverage_type_id)
     */
    public function coverageType()
    {
        return $this->belongsTo(EnumOption::class, 'coverage_type_id');
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
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }


    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
