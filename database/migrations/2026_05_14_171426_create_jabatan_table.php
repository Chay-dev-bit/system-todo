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
       Schema::create('jabatan', function (Blueprint $table) {
            $table->string('kantor_id', 5);
            $table->string('unit_id', 6);
            $table->string('id', 6);
            $table->string('nama_jabatan', 100)->nullable();
            $table->string('tingkat', 1)->nullable();
            $table->string('kls_jab', 2)->nullable();
            $table->string('atasan_bid', 6)->nullable();
            $table->string('atasan_jab', 6)->nullable();
            $table->string('created_by', 10)->nullable();
            $table->date('created_date')->nullable();
            $table->string('modified_by', 10)->nullable();
            $table->date('modified_date')->nullable();
            $table->string('approved_by', 10)->nullable();
            $table->date('approved_date')->nullable();
            $table->primary([
                'kantor_id',
                'unit_id',
                'id'
            ]);

            $table->foreign([
                'kantor_id',
                'unit_id'
            ])
            ->references([
                'kantor_id',
                'id'
            ])
            ->on('unit')
            ->noActionOnDelete()
            ->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan');
    }
};
