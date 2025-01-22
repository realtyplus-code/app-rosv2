<?php

namespace App\Models\Provider;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
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
}
