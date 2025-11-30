<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanDokumen extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dengan konvensi Laravel
    protected $table = 'dokumen';
    protected $primaryKey = 'kode_dokumen';

    // Tentukan kolom yang bisa diisi secara massal
    protected $fillable = ['id','kode_dokumen','id_pengajuan_beasiswa','nama_dokumen','link_dokumen'];


    public function pengajuanBeasiswa(){
        return $this->hasOne(pengajuanBeasiswa::class);
    }
}
