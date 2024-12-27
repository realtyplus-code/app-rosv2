<?php

namespace App\Models\ProviderService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderService extends Model
{
    use HasFactory;

    protected $table = 'provider_service';

    protected $fillable = [
        'provider_id',
        'service_id',
    ];
}
