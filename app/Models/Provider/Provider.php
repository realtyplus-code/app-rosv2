<?php

namespace App\Models\Provider;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Provider extends Authenticatable
{
    protected $fillable = [
        'user',//ok
        'email',//ok
        'password',//ok
        'name',//ok
        'country',
        'state',
        'city',
        'address',//ok
        'phone',//ok
        'code_number',//ok
        'code_country',//ok
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
