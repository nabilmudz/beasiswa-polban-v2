<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history_mahasiswa_penerima', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key; // Auto-incrementing ID for the primary key
            $table->string('nim'); // NIM (student identification)
            $table->string('nama_mahasiswa'); // Nama Mahasiswa (student name)
            $table->string('nama_prodi'); // Nama Prodi (program name)
            $table->string('nama_beasiswa'); // Nama Beasiswa (scholarship name)
            $table->timestamps(); // Created at and updated at timestamps
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_mahasiswa_penerima');
    }
};
