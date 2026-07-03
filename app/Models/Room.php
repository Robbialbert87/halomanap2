<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['unit_id', 'name', 'qr_code_path'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
