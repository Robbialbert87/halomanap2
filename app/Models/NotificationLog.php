<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NotificationLog extends Model
{
    protected $fillable = [
        'uuid',
        'ticket_id',
        'workflow_history_id',
        'recipient_user_id',
        'nomor_wa',
        'jenis',
        'isi_pesan',
        'status',
        'error_message',
        'sent_at',
        'read_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
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

    public function workflowHistory()
    {
        return $this->belongsTo(WorkflowHistory::class, 'workflow_history_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }
}
