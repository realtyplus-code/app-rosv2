<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Model;

class EnumOption extends Model
{
    protected $table = 'enum_options';
    protected $primaryKey = 'id';

    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'description',
        'valor1',
        'valor2',
        'is_father',
        'status',
        'orden'
    ];

    public function parent()
    {
        return $this->belongsTo(EnumOption::class, 'parent_id');
    }
}
