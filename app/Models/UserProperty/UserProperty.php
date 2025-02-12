<?php

namespace App\Models\UserProperty;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserProperty extends Model
{
    protected $fillable = [
        'user_id',
        'property_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
