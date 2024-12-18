<?php

namespace App\Models\Provider;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'address',
        'coverage_area',
        'services_offered',
        'contact_phone',
        'contact_email',
        'service_cost',
        'status',
    ];
}
