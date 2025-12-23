<?php

namespace App\Models;

use App\Models\BenefitBeasiswa;
use App\Models\JenjangPendidikan;
use App\Models\LinkBeasiswa;
use App\Models\PengajuanBeasiswa;
use App\Models\PosterBeasiswa;
use App\Models\SyaratBeasiswa;
use App\Models\SyaratDokumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Beasiswa extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dengan konvensi Laravel
    protected $table = 'beasiswa';
    public $incrementing = false;

    // Tentukan kolom yang bisa diisi secara massal
    protected $fillable = ['id','nama_beasiswa', 'deskripsi', 'youtube_url', 'jenis_beasiswa', 'tipe_beasiswa','kuota', 'sumber', 'tanggal_mulai', 'tanggal_berakhir', 'publish', 'allow_multiple'];


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
    // Relasi satu ke banyak dengan SyaratBeasiswa
    public function syaratBeasiswa()
    {
        return $this->belongsToMany(SyaratBeasiswa::class,'beasiswa_syarat_beasiswa');
    }

    // Relasi satu ke banyak dengan BenefitBeasiswa
    public function benefitBeasiswa()
    {
        return $this->belongsToMany(BenefitBeasiswa::class, 'beasiswa_benefit');
    }
    public function syaratDokumen()
    {
        return $this->belongsToMany(SyaratDokumen::class,'beasiswa_syarat_dokumen');
    }

    public function jenjangPendidikan()
    {
        return $this->hasMany(JenjangPendidikan::class);
    }

    public function pengajuanBeasiswa()
    {
        return $this->hasMany(PengajuanBeasiswa::class);
    }
    public function posterBeasiswa()
    {
        return $this->hasMany(PosterBeasiswa::class);
    }
    public function linkBeasiswa()
    {
        return $this->hasOne(LinkBeasiswa::class);
    }
}
