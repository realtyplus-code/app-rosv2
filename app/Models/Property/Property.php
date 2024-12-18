<?php

namespace App\Models\Property;

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

    public function propertyType()
    {
        return $this->belongsTo(EnumOption::class, 'property_type_id');
    }
}
