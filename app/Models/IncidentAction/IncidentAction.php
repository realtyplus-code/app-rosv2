<?php

namespace App\Models\IncidentAction;

use App\Models\User;
use App\Models\Incident\Incident;
use Illuminate\Database\Eloquent\Model;

class IncidentAction extends Model
{
    protected $fillable = [
        'incident_id',
        'action_date',
        'responsible_user_id',
        'action_description',
        'action_cost',
        'evidence',
    ];

    /**
     * Relación: Acción de Incidente - Incidente (incident_id)
     */
    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id');
    }

    /**
     * Relación: Acción de Incidente - Usuario Responsable (responsible_user_id)
     */
    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}
