<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SyaratBeasiswa extends Model
{
    use HasFactory;

    protected $table = 'syarat_beasiswa';
    public $incrementing = false;

    protected $fillable = ['syarat'];

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

    // Relasi ke Beasiswa (many to one)
    public function beasiswa()
    {
        return $this->belongsToMany(Beasiswa::class, 'beasiswa_syarat_beasiswa');
    }
}

