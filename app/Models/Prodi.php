<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    /** @use HasFactory<\Database\Factories\ProdiFactory> */
    use HasFactory;

    protected $table = 'prodi';

    protected $fillable = [
        'nama_prodi',
        'jurusan_id'
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'prodi_id');
    }
}
