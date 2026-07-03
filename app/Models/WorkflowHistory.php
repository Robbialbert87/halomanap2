<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkflowHistory extends Model
{
    protected $fillable = [
        'uuid',
        'ticket_id',
        'from_user_id',
        'to_user_id',
        'from_jabatan_id',
        'to_jabatan_id',
        'from_unit_id',
        'to_unit_id',
        'workflow_level',
        'action',
        'komentar',
        'lampiran',
        'status',
        'due_at',
        'completed_at',
    ];

    protected $casts = [
        'due_at'       => 'datetime',
        'completed_at' => 'datetime',
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

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function fromJabatan()
    {
        return $this->belongsTo(Jabatan::class, 'from_jabatan_id');
    }

    public function toJabatan()
    {
        return $this->belongsTo(Jabatan::class, 'to_jabatan_id');
    }

    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'from_unit_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'to_unit_id');
    }

    /**
     * Badge status untuk tampilan
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'baru'                => ['label' => 'Baru',                'class' => 'bg-blue-100 text-blue-800'],
            'didisposisikan'      => ['label' => 'Didisposisikan',      'class' => 'bg-purple-100 text-purple-800'],
            'menunggu_respon'     => ['label' => 'Menunggu Respon',     'class' => 'bg-yellow-100 text-yellow-800'],
            'dalam_penanganan'    => ['label' => 'Dalam Penanganan',    'class' => 'bg-indigo-100 text-indigo-800'],
            'eskalasi'            => ['label' => 'Eskalasi',            'class' => 'bg-red-100 text-red-800'],
            'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-orange-100 text-orange-800'],
            'selesai'             => ['label' => 'Selesai',             'class' => 'bg-green-100 text-green-800'],
            'ditutup'             => ['label' => 'Ditutup',             'class' => 'bg-gray-100 text-gray-800'],
            default               => ['label' => $this->status,         'class' => 'bg-gray-100 text-gray-800'],
        };
    }

    /**
     * Label action yang lebih ramah untuk ditampilkan
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'disposisi'      => 'Disposisi',
            'eskalasi'       => 'Eskalasi',
            'tangani_sendiri'=> 'Tangani Sendiri',
            'selesai'        => 'Selesaikan',
            'ditolak'        => 'Ditolak',
            'verifikasi'     => 'Verifikasi',
            'tutup'          => 'Tutup',
            default          => ucfirst($this->action),
        };
    }
}
