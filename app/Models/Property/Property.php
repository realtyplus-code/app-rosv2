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
        'photo',
        'photo1',
        'photo2',
        'photo3',
        'document',
        'country',
        'state',
        'city',
        'user_id',
        'expected_end_date_ros',
    ];

    public function getPhotoAttribute($value)
    {
        if ($value) {
            return Storage::disk('disk_property')->url($value);
        }
    }

    public function getPhoto1Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_property')->url($value);
        }
    }

    public function getPhoto2Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_property')->url($value);
        }
    }

    public function getPhoto3Attribute($value)
    {
        if ($value) {
            return Storage::disk('disk_property')->url($value);
        }
    }

    public function getDocumentAttribute($value)
    {
        if ($value) {
            return Storage::disk('disk_property')->url($value);
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function setExpectedEndDateRosAttribute($value)
    {
        $this->attributes['expected_end_date_ros'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function getExpectedEndDateRosAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
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
