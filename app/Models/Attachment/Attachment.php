<?php

namespace App\Models\Attachment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'attachable_id',
        'attachable_type',
        'file_path',
        'file_type',
        'name',
    ];

    /**
     * Get the parent attachable model (user or post).
     */
    public function attachable()
    {
        return $this->morphTo();
    }
}
