<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nip',
        'name',
        'username',
        'email',
        'password',
        'jabatan',
        'unit_kerja',
        'status_pegawai',
        'tanggal_masuk',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'role',
        'foto',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function pengajuanCuti()
    {
        return $this->hasMany(PengajuanCuti::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_masuk' => 'date',
        'tanggal_lahir' => 'date',
    ];
}
