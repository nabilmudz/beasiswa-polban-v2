<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $primaryKey = 'nim'; // Custom primary key

    // Tentukan nama tabel jika berbeda dengan konvensi Laravel
    protected $table = 'mahasiswa';

    // Tentukan kolom yang bisa diisi secara massal
    protected $fillable = [
        'nim',
        'semester',
        'tgl_lahir',
        'no_hp',
        'prodi_id',
        'angkatan',
        'user_id',
        'ipk_file',
        'ukt_file',
        'status_beasiswa',
        'nama_beasiswa_saat_ini'
    ];

    public function pengajuanBeasiswa()
    {
        return $this->hasMany(PengajuanBeasiswa::class, 'beasiswa_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    // Model Mahasiswa
    public function penerimaBeasiswa()
    {
        return $this->hasMany(PenerimaBeasiswa::class, 'nim', 'nim');
    }
}
