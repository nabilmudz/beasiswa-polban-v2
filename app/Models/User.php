<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_depan',
        'nama_belakang',
        'email',
        'jenis_kelamin',  // Changed from jenis_kelamin
        'nim',
        'no_hp',
        'user_img',  // Add this for image uploads
        'password',
        'emailVerif',
        'id',
        'isActive'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Model User
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id', 'id');
    }

    public function reviewer()
{
    return $this->hasOne(Reviewer::class, 'user_id', 'id');
}

}
