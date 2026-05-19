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
        Schema::create('pengguna', function (Blueprint $table) {
            $table->string('kantor_id', 5);
            $table->string('nip', 12)->primary();
            $table->string('nama_lengkap', 50);
            $table->string('nama_awal', 50);
            $table->string('nama_akhir', 50);
            $table->string('nama_pemakai', 30);
            $table->string('password', 50);
            $table->string('level_user', 1);
            $table->string('aktif', 1);
            $table->string('created_by', 20);
            $table->dateTime('created_date')->default(now());
            $table->string('modified_by', 20)->nullable();
            $table->dateTime('modified_date')->nullable();
            $table->string('pwd', 100);
            $table->integer('chg_pwd');
            $table->string('approved_by', 30)->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->dateTime('tgl_kirim')->nullable();
            $table->string('nama_pengirim', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peengguna');
    }
};
