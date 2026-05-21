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
        Schema::table('projects', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['pic_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['approved_by']);

            // Change column types to string (for nip)
            $table->string('pic_id')->change();
            $table->string('created_by')->change();
            $table->string('updated_by')->nullable()->change();
            $table->string('verified_by')->nullable()->change();
            $table->string('approved_by')->nullable()->change();

            // Add new foreign keys to pengguna table
            $table->foreign('pic_id')
                ->references('nip')
                ->on('pengguna')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('created_by')
                ->references('nip')
                ->on('pengguna')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('updated_by')
                ->references('nip')
                ->on('pengguna')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('verified_by')
                ->references('nip')
                ->on('pengguna')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('approved_by')
                ->references('nip')
                ->on('pengguna')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop new foreign keys
            $table->dropForeign(['pic_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['approved_by']);

            // Change column types back to bigint
            $table->foreignId('pic_id')->change();
            $table->foreignId('created_by')->change();
            $table->foreignId('updated_by')->nullable()->change();
            $table->foreignId('verified_by')->nullable()->change();
            $table->foreignId('approved_by')->nullable()->change();

            // Add back original foreign keys to users table
            $table->foreign('pic_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }
};
