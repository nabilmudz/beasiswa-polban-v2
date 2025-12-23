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
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('ipk_file')->nullable()->after('angkatan');
            $table->string('ukt_file')->nullable()->after('ipk_file');
            $table->boolean('status_beasiswa')->default(false)->after('ukt_file')->comment('0 = tidak punya beasiswa, 1 = sedang menjalani beasiswa');
            $table->string('nama_beasiswa_saat_ini')->nullable()->after('status_beasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['ipk_file', 'ukt_file', 'status_beasiswa', 'nama_beasiswa_saat_ini']);
        });
    }
};
