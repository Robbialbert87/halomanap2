<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    use HasUuids;

    protected $fillable = [
        'ticket_id', 'user_id', 'attachment_type', 'description',
        'file_name', 'file_path', 'mime_type', 'file_size'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
