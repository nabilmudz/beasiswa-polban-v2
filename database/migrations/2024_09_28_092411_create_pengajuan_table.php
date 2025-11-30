<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kode_status', function (Blueprint $table) {
            $table->id();
            $table->string('isi_status');
        });

        Schema::create('pengajuan_beasiswa', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key;
            $table->string("nim",9);
            $table->uuid('beasiswa_id');
            $table->date('tanggal_pengajuan');
            $table->foreignId('status')->constrained('kode_status');
            $table->text('komentar')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('beasiswa_id')->references('id')->on('beasiswa')->onDelete('cascade');
        });

        Schema::create('dokumen', function(Blueprint $table){
            $table->string('kode_dokumen')->primary();
            $table->uuid("id_pengajuan_beasiswa");
            $table->string("nama_dokumen");
            $table->text("link_dokumen");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->foreign('id_pengajuan_beasiswa')->references('id')->on('pengajuan_beasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_dokumen');
        Schema::dropIfExists('pengajuan_beasiswa');
        Schema::dropIfExists('kode_status');
    }
};
