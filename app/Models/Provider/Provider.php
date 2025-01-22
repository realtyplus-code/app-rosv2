<?php

namespace App\Models\Provider;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Provider extends Authenticatable
{
    protected $fillable = [
        'user',
        'email',
        'password',
        'name',
        'country',
        'state',
        'city',
        'address',
        'phone',
        'code_number',
        'code_country',
        'language_id',
        'coverage_area',
        'website',
        'status',
        'user_id',
    ];

    // Agregar la propiedad oculta para la contraseña
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
