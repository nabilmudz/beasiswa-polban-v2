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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_depan')->nullable();
            $table->string('nama_belakang')->nullable();
            $table->string('email')->unique();
            $table->enum('jenis_kelamin', ['Pria', 'Wanita'])->nullable();
            $table->string('email_verification_token')->nullable();
            $table->text('foto')->nullable();
            $table->string('password'); // Menambahkan kolom password
            $table->boolean('emailVerif')->default(false); // Menambahkan kolom emailVerif dengan nilai default false
            $table->boolean('isActive')->default(false);
            $table->timestamps();
        });



        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('role',function(Blueprint $table){
            $table->id();
            $table->string("role_name");
            $table->timestamps();
        });

        Schema::create('reviewer', function(Blueprint $table){
            $table->unsignedBigInteger("user_id")->unique();
            $table->string("nip",20)->primary();
            $table->unsignedBigInteger("role_id");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');;
            $table->timestamps();
        });

        Schema::create('jurusan', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nama_jurusan');
            $table->unsignedBigInteger('kajur_id')->nullable();
            $table->foreign('kajur_id')->references('user_id')->on('reviewer')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('prodi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prodi');
            $table->unsignedBigInteger('jurusan_id');
            $table->foreign('jurusan_id')->references('id')->on('jurusan')->onDelete('cascade');
            $table->timestamps();

        });

        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('nim',9)->primary();
            $table->tinyInteger('semester');
            $table->date('tgl_lahir');
            $table->unsignedBigInteger('prodi_id');
            $table->string('no_hp')->unique();
            $table->year('angkatan');

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviewer');
        Schema::dropIfExists('role');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('prodi'); // prodi depends on jurusan, so drop it first
        Schema::dropIfExists('jurusan');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};
