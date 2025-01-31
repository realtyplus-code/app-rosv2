<?php

namespace App\Models\InsuranceProperty;

use Illuminate\Database\Eloquent\Model;

class InsuranceProperty extends Model
{
    protected $table = 'insurance_property';

    protected $fillable = [
        'property_id',
        'insurance_id',
    ];
}
