<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPropertyHistory extends Model
{
    use HasFactory;

    protected $table = 'user_property_histories';

    protected $fillable = [
        'user_id',
        'property_id',
        'type_user',
    ];
}
