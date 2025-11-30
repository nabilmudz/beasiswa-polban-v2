<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    /** @use HasFactory<\Database\Factories\JurusanFactory> */
    use HasFactory;

    protected $table = 'jurusan';
    protected $fillable = [
        'nama_jurusan',
        'kajur_id'
    ];

    public function prodi()
    {
        $this->hasMany(Prodi::class, 'jurusan_id');
    }

    public function kajur()
    {
        $this->belongsTo(Reviewer::class, 'kajur_id');
    }

    public function jenjang()
    {
        $this->hasMany(JenjangPendidikan::class, 'jurusan');
    }
}
