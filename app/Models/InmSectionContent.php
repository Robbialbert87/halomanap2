<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InmSectionContent extends Model
{
    protected $fillable = ['bulan', 'tahun', 'analisis_capaian', 'rencana_tindak_lanjut'];
}
