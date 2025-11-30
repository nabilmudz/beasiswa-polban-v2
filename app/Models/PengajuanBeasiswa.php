<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PengajuanBeasiswa extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dengan konvensi Laravel
    protected $table = 'pengajuan_beasiswa';

    // Tentukan kolom yang bisa diisi secara massal
    protected $fillable = ['beasiswa_id','nim','tanggal_pengajuan','status', 'komentar'];

    protected $keyType = 'string'; // UUID disimpan sebagai string

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }


    public function Beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function Mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function PengajuanDokumen()
    {
        return $this->hasMany(PengajuanDokumen::class);
    }

    public function Status()
    {
        return $this->belongsTo(KodeStatus::class, 'status');
    }
}
