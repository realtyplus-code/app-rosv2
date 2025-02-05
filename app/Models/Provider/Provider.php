<?php

namespace App\Models\Provider;

use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Provider extends Authenticatable
{
    use HasRoles;
    protected $guard_name = 'providers';

    protected $fillable = [
        'user',
        'email',
        'password',
        'name',
        'country',
        'state',
        'city',
        'address',
        'contact_phone',
        'code_number',
        'code_country',
        'language_id',
        'coverage_area',
        'website',
        'status',
        'user_id',
        'service_cost',
    ];

    // Agregar la propiedad oculta para la contraseña
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mutadores para created_at y updated_at
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
