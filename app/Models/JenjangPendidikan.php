<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JenjangPendidikan extends Model
{
    use HasFactory;

    protected $table = 'jenjang_pendidikan';

    protected $fillable = ['beasiswa_id','jenjang'];

    protected $primaryKey = ['beasiswa_id','jenjang'];

    public $incrementing = false;


    // Relasi ke Beasiswa (many to one)
    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }
}
