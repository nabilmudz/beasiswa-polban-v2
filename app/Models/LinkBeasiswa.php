<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LinkBeasiswa extends Model
{
    protected $table = 'link_beasiswa';
    public $incrementing = false;
    protected $fillable = ['beasiswa_id', 'link_beasiswa'];

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


    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }
}
