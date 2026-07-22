<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuditTrail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'user_id',
        'user_role',
        'user_jabatan',
        'user_unit',
        'ip_address',
        'user_agent',
        'action',
        'model',
        'model_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catat aktivitas ke audit trail.
     */
    public static function log(string $action, ?string $model = null, mixed $modelId = null, array $payload = []): void
    {
        $user = auth()->user();

        static::create([
            'user_id' => $user?->id,
            'user_role' => $user?->roles->first()?->name,
            'user_jabatan' => $user?->jabatan?->nama,
            'user_unit' => $user?->unit?->nama,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'payload' => $payload ?: null,
        ]);
    }
}
