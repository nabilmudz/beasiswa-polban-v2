<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeStatus extends Model
{
    protected $table = "kode_status";

    public function pengajuan(){
        return $this->hasMany(PengajuanBeasiswa::class);
    }
}

