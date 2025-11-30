<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('beasiswa', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key
            $table->string('nama_beasiswa');
            $table->text('deskripsi');
            $table->enum('tipe_beasiswa',['kipk','internal','eksternal']);
            $table->enum('jenis_beasiswa', ['full', 'half']); // enum jenis_beasiswa
            $table->integer('kuota');
            $table->string('sumber');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->boolean('publish');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // Table untuk syarat_beasiswa (pivot table)
        Schema::create('syarat_beasiswa', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key
            $table->string('syarat');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // Table untuk benefit_beasiswa (pivot table)
        Schema::create('benefit_beasiswa', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key
            $table->string('benefit');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // Table untuk syarat_dokumen (pivot table)
        Schema::create('syarat_dokumen', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key
            $table->string('dokumen');
            $table->text('link_dokumen');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('poster_beasiswa', function (Blueprint $table) {
            $table->uuid('beasiswa_id');
            $table->text('link_poster');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('beasiswa_id')->references('id')->on('beasiswa')->onDelete('cascade');
        });

        // Table untuk jenjang_pendidikan ()
        Schema::create('jenjang_pendidikan', function (Blueprint $table) {
            $table->uuid('beasiswa_id');
            $table->string('jenjang');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('beasiswa_id')->references('id')->on('beasiswa')->onDelete('cascade');
        });

        // Tabel pivot untuk beasiswa dan benefit_beasiswa
        Schema::create('beasiswa_benefit', function (Blueprint $table) {
            $table->uuid('beasiswa_id');
            $table->uuid('benefit_beasiswa_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('beasiswa_id')->references('id')->on('beasiswa')->onDelete('cascade');
            $table->foreign('benefit_beasiswa_id')->references('id')->on('benefit_beasiswa')->onDelete('cascade');
        });

        // Tabel pivot untuk beasiswa dan syarat_dokumen
        Schema::create('beasiswa_syarat_dokumen', function (Blueprint $table) {
            $table->uuid('beasiswa_id');
            $table->uuid('syarat_dokumen_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('beasiswa_id')->references('id')->on('beasiswa')->onDelete('cascade');
            $table->foreign('syarat_dokumen_id')->references('id')->on('syarat_dokumen')->onDelete('cascade');
        });

        // Tabel pivot untuk beasiswa dan syarat_beasiswa
        Schema::create('beasiswa_syarat_beasiswa', function (Blueprint $table) {
            $table->uuid('beasiswa_id');
            $table->uuid('syarat_beasiswa_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('beasiswa_id')->references('id')->on('beasiswa')->onDelete('cascade');
            $table->foreign('syarat_beasiswa_id')->references('id')->on('syarat_beasiswa')->onDelete('cascade');
        });

        // Weak Table untuk link beasiswwa
        Schema::create('link_beasiswa', function (Blueprint $table){
            $table->uuid('id')->primary(); // UUID as primary key
            $table->uuid('beasiswa_id');
            $table->string('link_beasiswa');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('beasiswa_id')->references('id')->on('beasiswa')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syarat_dokumen');
        Schema::dropIfExists('benefit_beasiswa');
        Schema::dropIfExists('syarat_beasiswa');
        Schema::dropIfExists('jenjang_pendidikan');
        Schema::dropIfExists('poster_beasiswa');
        Schema::dropIfExists('beasiswa');
        Schema::dropIfExists('beasiswa_benefit');
        Schema::dropIfExists('beasiswa_syarat_dokumen');
        Schema::dropIfExists('beasiswa_syarat_beasiswa');

    }
};
