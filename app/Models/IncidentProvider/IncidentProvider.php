<?php

namespace App\Models\IncidentProvider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentProvider extends Model
{
    use HasFactory;

    protected $table = 'incident_provider';

    protected $fillable = [
        'incident_id',
        'provider_id',
    ];
}
