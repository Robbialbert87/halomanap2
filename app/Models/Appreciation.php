<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appreciation extends Model
{
    protected $fillable = [
        'name', 'rating', 'message'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];
}
