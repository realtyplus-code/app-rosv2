<?php

namespace App\Models\IncidentAction;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Incident\Incident;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class IncidentAction extends Model
{
    protected $fillable = [
        'incident_id',
        'action_date',
        'responsible_user_id',
        'responsible_user_type',
        'action_description',
        'action_cost',
        'photo',
        'photo1',
        'photo2',
        'photo3',
        'document',
        'document1',
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

    public function setActionDateAttribute($value)
    {
        $this->attributes['action_date'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getActionDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getPhotoAttribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident_action')->url($value);
        }
    }

    public function getPhoto1Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident_action')->url($value);
        }
    }

    public function getPhoto2Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident_action')->url($value);
        }
    }

    public function getPhoto3Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident_action')->url($value);
        }
    }

    public function getDocumentAttribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident_action')->url($value);
        }
    }

    public function getDocument1Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_incident_action')->url($value);
        }
    }
}
