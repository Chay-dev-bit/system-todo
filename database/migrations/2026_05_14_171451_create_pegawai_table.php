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
        
        Schema::create('pegawai', function (Blueprint $table) {
            $table->string('nip', 12)->primary();
            $table->string('nama', 50)->nullable();
            $table->string('tempat_lahir', 20)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jen_kel', 1)->nullable();
            $table->string('alamat', 500)->nullable();
            $table->string('agama', 12)->nullable();
            $table->string('status_perkawinan', 5)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->string('al_email', 55)->nullable();
            $table->string('aktif', 50)->nullable();
            $table->string('kantor_id', 5)->nullable();
            $table->string('unit_id', 6)->nullable();
            $table->string('jabatan_id', 6)->nullable();
            $table->string('created_by', 20)->nullable();
            $table->date('created_date')->nullable();
            $table->string('modified_by', 20)->nullable();
            $table->date('modified_date')->nullable();
            $table->string('approved_by', 20)->nullable();
            $table->date('approved_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
