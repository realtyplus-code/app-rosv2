<?php

namespace App\Models\Incident;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Property\Property;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use App\Models\Configuration\EnumOption;

class Incident extends Model
{
    protected $fillable = [
        'property_id',
        'description',
        'report_date',
        'status_id',
        'reported_by',
        'incident_type_id',
        'priority_id',
        'cost',
        'payer_id',
        'photo',
        'photo1',
        'photo2',
        'photo3',
        'pdf',
        'pdf1',
    ];

    /**
     * Relación: Incidente - Propiedad (property_id)
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * Relación: Incidente - Usuario que reporta (reported_by)
     */
    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Relación: Incidente - Estado (status_id)
     */
    public function status()
    {
        return $this->belongsTo(EnumOption::class, 'status_id');
    }

    /**
     * Relación: Incidente - Tipo de incidente (incident_type_id)
     */
    public function incidentType()
    {
        return $this->belongsTo(EnumOption::class, 'incident_type_id');
    }

    /**
     * Relación: Incidente - Prioridad (priority_id)
     */
    public function priority()
    {
        return $this->belongsTo(EnumOption::class, 'priority_id');
    }

    /**
     * Relación: Incidente - Pagador (payer_id)
     */
    public function payer()
    {
        return $this->belongsTo(EnumOption::class, 'payer_id');
    }

    public function setReportDateAttribute($value)
    {
        $this->attributes['report_date'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }


    public function getReportDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getPhotoAttribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident')->url($value);
        }
    }

    public function getPhoto1Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident')->url($value);
        }
    }

    public function getPhoto2Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident')->url($value);
        }
    }

    public function getPhoto3Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident')->url($value);
        }
    }

    public function getPdfAttribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident')->url($value);
        }
    }

    public function getPdf1Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident')->url($value);
        }
    }

}
