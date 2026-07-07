<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    protected $fillable = ['name', 'color', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function units()
    {
        return $this->hasMany(Unit::class, 'jenis', 'name');
    }
}
