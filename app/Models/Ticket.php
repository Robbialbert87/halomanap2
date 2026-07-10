<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number', 'type', 'category_id', 'room_id', 'is_anonymous',
        'reporter_name', 'reporter_phone', 'title', 'description', 'attachment_path',
        'status', 'sla_id', 'sla_breached', 'rating', 'review', 'notification_seen_at'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function category()
    {
        return $this->belongsTo(ReportCategory::class, 'category_id');
    }

    public function sla()
    {
        return $this->belongsTo(Sla::class);
    }

    public function histories()
    {
        return $this->hasMany(TicketHistory::class)->orderBy('created_at', 'desc');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class)->orderBy('created_at', 'asc');
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class)->orderBy('created_at', 'desc');
    }

    public function disposition()
    {
        return $this->hasOne(Disposition::class);
    }

    public function workflows()
    {
        return $this->hasMany(WorkflowHistory::class);
    }

    public function activeWorkflow()
    {
        return $this->hasOne(WorkflowHistory::class)
            ->whereNotIn('status', ['ditutup', 'didisposisikan', 'eskalasi', 'selesai'])
            ->latestOfMany();
    }
}
