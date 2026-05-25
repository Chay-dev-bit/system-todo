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
            $table->enum('approval_status', ['pending', 'verified', 'approved', 'rejected'])
                ->default('pending')
                ->after('status');

            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->timestamp('approved_at')->nullable()->after('approved_by');

            $table->text('rejection_note')->nullable()->after('approved_at');
            $table->string('rejected_by')->nullable()->after('rejection_note');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');

            $table->foreign('rejected_by')
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
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'approval_status',
                'verified_at',
                'approved_at',
                'rejection_note',
                'rejected_by',
                'rejected_at',
            ]);
        });
    }
};
