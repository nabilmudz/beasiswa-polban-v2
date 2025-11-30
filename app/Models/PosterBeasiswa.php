<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PosterBeasiswa extends Model
{
    use HasFactory;

    protected $table = 'poster_beasiswa';

    protected $fillable = ['beasiswa_id', 'link_poster'];
    protected $primaryKey = ['beasiswa_id', 'link_poster'];
    public $incrementing = false;

    // Relasi ke Beasiswa (many to one)
    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }
}
