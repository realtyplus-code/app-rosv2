<?php

namespace App\Models\Provider;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'address',
        'coverage_area',
        'contact_phone',
        'code_number',
        'code_country',
        'contact_email',
        'service_cost',
        'status',
    ];
}
