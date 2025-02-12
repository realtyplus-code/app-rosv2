<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyHistory extends Model
{
    use HasFactory;

    protected $table = 'property_histories';

    protected $fillable = [
        'property_id',
        'name',
        'address',
        'status',
        'country',
        'state',
        'city',
        'property_type_id',
        'user_id',
        'client_ros_id',
        'expected_end_date_ros',
    ];
}
