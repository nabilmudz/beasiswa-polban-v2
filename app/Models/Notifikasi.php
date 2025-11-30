<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    // Jika nama tabel berbeda dari nama model, tentukan nama tabelnya
    protected $table = 'notifikasi';



    // Tentukan kolom mana saja yang dapat diisi (mass assignable)
    protected $fillable = [
        'user_id',
        'id_pengajuan_beasiswa',
        'status',
        'read',
    ];

    // Tentukan jika ada relasi, contoh ke model User (user yang menerima notifikasi)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Misalnya jika Anda ingin mengambil pengajuan beasiswa terkait notifikasi ini
    public function pengajuanBeasiswa()
    {
        return $this->belongsTo(PengajuanBeasiswa::class, 'id_pengajuan_beasiswa');
    }
}
