<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewerFactory> */
    use HasFactory;

    protected $primaryKey = 'nip'; // Custom primary key
    protected $table = 'reviewer';
    protected $fillable = ['nim','semester','tgl_lahir','no_hp','jurusan_id','prodi_id','angkatan','user_id', 'role_id'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id'); // Menghubungkan role_id di reviewer dengan id di role
    }

    // Relasi ke User berdasarkan user_id (jika perlu)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}