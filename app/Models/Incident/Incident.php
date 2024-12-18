<?php

namespace App\Models\Incident;

use App\Models\User;
use App\Models\Property\Property;
use App\Models\Provider\Provider;
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
        'assigned_responsible',
        'cost',
        'payer_id',
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
     * Relación: Incidente - Responsable asignado (assigned_responsible)
     */
    public function assignedResponsible()
    {
        return $this->belongsTo(Provider::class, 'assigned_responsible');
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
}
