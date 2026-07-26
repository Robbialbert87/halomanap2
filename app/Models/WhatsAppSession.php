<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WhatsAppSession extends Model
{
    protected $table = 'whatsapp_sessions';

    protected $fillable = [
        'user_id',
        'session_id',
        'phone_number',
        'status',
        'qr_code',
        'webhook_config',
        'connected_at',
        'disconnected_at',
    ];

    protected $casts = [
        'webhook_config' => 'array',
        'connected_at' => 'immutable_datetime',
        'disconnected_at' => 'immutable_datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
