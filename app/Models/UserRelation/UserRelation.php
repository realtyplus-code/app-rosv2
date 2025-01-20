<?php

namespace App\Models\UserRelation;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRelation extends Model
{
    use HasFactory;

    protected $table = 'users_relations';

    protected $fillable = [
        'user_id',
        'user_id_related',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
