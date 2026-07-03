<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DispositionActivity extends Model
{
    use HasUuids;

    protected $fillable = [
        'disposition_id', 'user_id', 'activity', 'description', 'ip_address'
    ];

    public function disposition()
    {
        return $this->belongsTo(Disposition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
