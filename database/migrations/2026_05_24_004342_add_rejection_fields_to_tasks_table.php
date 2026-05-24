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
            $table->text('rejection_note')->nullable();
            $table->foreignIdFor(\App\Models\Pengguna::class, 'rejected_by')->nullable()->constrained('pengguna', 'nip')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('rejected_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['rejection_note', 'rejected_by', 'rejected_at']);
        });
    }
};
