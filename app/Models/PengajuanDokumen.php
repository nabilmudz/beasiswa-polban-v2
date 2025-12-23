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
    public $incrementing = false; // Primary key bukan auto-increment
    protected $keyType = 'string'; // Primary key bertipe string

    // Tentukan kolom yang bisa diisi secara massal
    protected $fillable = ['kode_dokumen','id_pengajuan_beasiswa','nama_dokumen','link_dokumen'];


    public function pengajuanBeasiswa(){
        return $this->hasOne(pengajuanBeasiswa::class);
    }
}
