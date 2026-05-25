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
            $table->string('asmen_id')->nullable()->after('pic_id');
            $table->string('manajer_id')->nullable()->after('asmen_id');

            $table->foreign('asmen_id')
                ->references('nip')
                ->on('pengguna')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('manajer_id')
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
            $table->dropForeign(['asmen_id']);
            $table->dropForeign(['manajer_id']);
            $table->dropColumn(['asmen_id', 'manajer_id']);
        });
    }
};
