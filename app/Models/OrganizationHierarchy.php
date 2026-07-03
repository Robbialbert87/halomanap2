<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OrganizationHierarchy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'unit_id',
        'jabatan_id',
        'parent_jabatan_id',
        'urutan_level',
        'workflow_level',
        'is_workflow_start',
        'is_workflow_end',
        'can_escalate',
        'status',
    ];

    protected $casts = [
        'is_workflow_start' => 'boolean',
        'is_workflow_end'   => 'boolean',
        'can_escalate'      => 'boolean',
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

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function parentJabatan()
    {
        return $this->belongsTo(Jabatan::class, 'parent_jabatan_id');
    }
}
