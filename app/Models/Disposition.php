<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disposition extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'ticket_id', 'unit_id', 'head_user_id', 'assigned_user_id', 'created_by',
        'priority', 'instruction', 'deadline', 'status', 
        'accepted_at', 'completed_at', 'verified_at'
    ];

    protected $casts = [
        'deadline' => 'date',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function headUser()
    {
        return $this->belongsTo(User::class, 'head_user_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activities()
    {
        return $this->hasMany(DispositionActivity::class)->orderBy('created_at', 'asc');
    }
}
