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
        Schema::table('pengajuan_beasiswa', function (Blueprint $table) {
            // Menambahkan kolom user_id_pengaju untuk mencatat siapa yang mengajukan beasiswa
            // Bisa mahasiswa sendiri atau ketua jurusan yang mengajukan untuk mahasiswa
            $table->unsignedBigInteger('user_id_pengaju')->nullable()->after('nim');
            $table->foreign('user_id_pengaju')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_beasiswa', function (Blueprint $table) {
            $table->dropForeign(['user_id_pengaju']);
            $table->dropColumn('user_id_pengaju');
        });
    }
};
