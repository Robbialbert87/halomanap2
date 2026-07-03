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
        'is_public',
        'parent_id',
        'entry_jabatan_id',
        'keterangan',
        'status',
        'head_user_id',
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

    public function parent()
    {
        return $this->belongsTo(Unit::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Unit::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'unit_id');
    }

    public function entryJabatan()
    {
        return $this->belongsTo(Jabatan::class, 'entry_jabatan_id');
    }
}
