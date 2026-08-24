<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapPengaduan extends Model
{
    protected $fillable = [
        'tanggal',
        'nama',
        'nomor_pelapor',
        'via_pengaduan',
        'kategori',
        'keluhan',
        'bukti',
        'tindak_lanjut',
        'status',
        'tanggal_selesai',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
