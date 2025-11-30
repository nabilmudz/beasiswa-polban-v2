<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryMahasiswaPenerima extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'history_mahasiswa_penerima'; // Sesuaikan dengan nama tabel Anda

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nim',
        'nama_mahasiswa',
        'nama_prodi',
        'nama_beasiswa',
        // 'created_at' dan 'updated_at' biasanya dihandle otomatis oleh Eloquent
        // jika Anda tidak mengisinya secara manual dan kolomnya ada di tabel.
    ];

    /**
     * Atribut yang harus berupa instance tanggal.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Mendefinisikan bahwa model ini tidak menggunakan auto-incrementing ID
     * jika 'id' Anda bukan integer auto-increment.
     * Jika 'id' adalah bigint auto-increment, baris ini tidak perlu.
     *
     * @var bool
     */
    // public $incrementing = false;

    /**
     * Tipe data dari primary key.
     * Jika 'id' Anda bukan integer, set ini.
     *
     * @var string
     */
    // protected $keyType = 'string';


    /**
     * Mendapatkan data mahasiswa yang terkait dengan history penerima beasiswa ini.
     * Asumsi: tabel 'mahasiswa' memiliki kolom 'nim' sebagai primary key atau unique key yang cocok.
     */
    public function mahasiswa()
    {
        // Argumen kedua adalah foreign key di tabel 'history_mahasiswa_penerima' (yaitu nim)
        // Argumen ketiga adalah owner key di tabel 'mahasiswa' (yaitu nim)
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    /**
     * Jika Anda juga ingin menghubungkan langsung ke User melalui Mahasiswa
     * (ini bersifat opsional dan bisa juga diakses melalui $this->mahasiswa->user)
     */
    public function user()
    {
        // Pastikan model Mahasiswa memiliki relasi 'user' yang benar
        return $this->hasOneThrough(User::class, Mahasiswa::class, 'nim', 'id', 'nim', 'user_id');
        // Argumen untuk hasOneThrough:
        // 1. Model tujuan akhir (User)
        // 2. Model perantara (Mahasiswa)
        // 3. Foreign key di model perantara yang merujuk ke model ini (nim di Mahasiswa merujuk ke nim di HistoryPenerimaBeasiswa)
        // 4. Foreign key di model tujuan yang merujuk ke model perantara (id di User merujuk ke user_id di Mahasiswa)
        // 5. Local key di model ini (nim)
        // 6. Local key di model perantara (user_id)
        // Perlu disesuaikan dengan struktur kunci primer/asing Anda.
        // Cara yang lebih sederhana adalah mengaksesnya melalui $history->mahasiswa->user
    }

}