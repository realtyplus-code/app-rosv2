<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryRelation extends Model
{
    use HasFactory;

    protected $table = 'country_relations';

    protected $fillable = [
        'country_id',
        'related_id',
        'type',
    ];
}
