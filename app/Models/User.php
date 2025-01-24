<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Models\UserRelation\UserRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'user',
        'email',
        'password',
        'phone',
        'code_number',
        'code_country',
        'photo',
        'country',
        'state',
        'city',
        'address',
        'language_id', // Añadir este campo
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime'
        ];
    }

    public function getPhotoAttribute($value)
    {
        if ($value) {
            return Storage::disk('disk_user')->url($value);
        }
    }

    public function relatedUsers()
    {
        return $this->hasMany(UserRelation::class, 'user_id_related');
    }
}
