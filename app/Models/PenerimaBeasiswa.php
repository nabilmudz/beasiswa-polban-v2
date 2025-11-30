<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PenerimaBeasiswa extends Model
{
     // Tentukan nama tabel jika berbeda dengan konvensi Laravel
     protected $table = 'penerima_beasiswa';

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
     // Tentukan kolom yang bisa diisi secara massal
     protected $fillable = ['nim','beasiswa_id'];

     public function beasiswa()
     {
     return $this->belongsTo(Beasiswa::class, 'beasiswa_id');
     }
}
