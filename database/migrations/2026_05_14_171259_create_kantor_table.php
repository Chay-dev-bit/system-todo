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
        Schema::create('kantor', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('nama', 50);
            $table->string('alamat', 50);
            $table->string('kota', 30);
            $table->string('telp', 15);
            $table->string('email', 50);
            $table->date('created_date')->nullable();
            $table->string('created_by', 30)->nullable();
            $table->date('modified_date')->nullable();
            $table->string('modified_by', 30)->nullable();
            $table->string('kantor_induk', 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kantor');
    }
};
