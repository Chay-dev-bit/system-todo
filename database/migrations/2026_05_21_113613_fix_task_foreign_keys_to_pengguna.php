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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            $table->string('assigned_to')->change();
            $table->string('created_by')->nullable()->change();
            $table->string('updated_by')->nullable()->change();
            
            $table->foreign('assigned_to')->references('nip')->on('pengguna')->onDelete('cascade');
            $table->foreign('created_by')->references('nip')->on('pengguna')->onDelete('set null');
            $table->foreign('updated_by')->references('nip')->on('pengguna')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            $table->unsignedBigInteger('assigned_to')->change();
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
            
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
