<?php

namespace App\Models\Property;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Configuration\EnumOption;

class Property extends Model
{
    protected $fillable = [
        'name',
        'address',
        'status',
        'property_type_id',
        'country',
        'state',
        'city',
        'user_id',
        'client_ros_id',
        'expected_end_date_ros',
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function setExpectedEndDateRosAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['expected_end_date_ros'] = Carbon::parse($value)->format('Y-m-d');
        } else {
            $this->attributes['expected_end_date_ros'] = null;
        }
    }

    public function getExpectedEndDateRosAttribute($value)
    {
        if (!empty($value)) {
            return Carbon::parse($value)->format('Y-m-d');
        } else {
            return null;
        }
    }

    public function propertyType()
    {
        return $this->belongsTo(EnumOption::class, 'property_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
