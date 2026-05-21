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
            $table->string('verified_by')->nullable()->after('attachment');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->string('approved_by')->nullable()->after('verified_at');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            
            $table->foreign('verified_by')->references('nip')->on('pengguna')->onDelete('set null');
            $table->foreign('approved_by')->references('nip')->on('pengguna')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['verified_by', 'verified_at', 'approved_by', 'approved_at']);
        });
    }
};
