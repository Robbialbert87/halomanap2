<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['uuid', 'nip', 'nama', 'email', 'phone_number', 'password', 'unit_id', 'jabatan_id', 'status', 'last_login_at', 'last_login_ip'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function getRoleGroup(): string
    {
        $role = $this->roles->first()?->name;
        $kategori = $this->jabatan?->kategori_jabatan;

        if ($role === 'Super Admin' || $role === 'Admin Pengaduan') {
            return 'admin';
        }
        if ($kategori === 'Kepala Unit' || $role === 'Kepala Unit') {
            return 'kepala_unit';
        }
        if ($role === 'Kepala Ruangan' || $kategori === 'Kepala Ruangan') {
            return 'head_unit';
        }
        if (in_array($kategori, ['Kasi', 'Kasubbag'])) {
            return 'kasi';
        }
        if (in_array($kategori, ['Kabid', 'Kabag'])) {
            return 'kabid';
        }
        if ($kategori === 'Direktur' || $role === 'Direktur') {
            return 'direktur';
        }

        return 'admin';
    }

    public function isKepalaUnit(): bool
    {
        return $this->getRoleGroup() === 'kepala_unit';
    }

    public function isKasi(): bool
    {
        return $this->getRoleGroup() === 'kasi';
    }

    public function isKabid(): bool
    {
        return $this->getRoleGroup() === 'kabid';
    }

    public function isDirektur(): bool
    {
        return $this->getRoleGroup() === 'direktur';
    }

    public function isAdmin(): bool
    {
        return $this->getRoleGroup() === 'admin';
    }

    public function isHeadUnit(): bool
    {
        return $this->getRoleGroup() === 'head_unit';
    }

    public function getRoleLabel(): string
    {
        return match ($this->getRoleGroup()) {
            'kabid' => 'Kabid / Kabag',
            'kasi' => 'Kasi / Kasubbag',
            'kepala_unit' => 'Kepala Unit',
            'head_unit' => 'Kepala Ruangan',
            'direktur' => 'Direktur',
            default => 'Pegawai',
        };
    }

    public function getRoutePrefix(): string
    {
        return str_replace('_', '-', $this->getRoleGroup());
    }
}
