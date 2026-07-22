<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'kode',
        'nama',
        'jenis',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Backward compatibility for old code accessing $unit->name
     */
    public function getNameAttribute()
    {
        return $this->nama;
    }

    public function users()
    {
        return $this->hasMany(User::class, 'unit_id');
    }
}
