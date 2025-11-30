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
        Schema::create('penerima_beasiswa', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key;
            $table->string('nim',9);
            $table->uuid('beasiswa_id');
            $table->timestamps();

            $table->foreign('beasiswa_id')->references('id')->on('beasiswa')->onDelete('cascade');
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima_beasiswa');
    }
};
