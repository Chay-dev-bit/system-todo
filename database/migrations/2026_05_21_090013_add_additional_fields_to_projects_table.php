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
            $table->date('start_date')->nullable()->after('description');
            $table->date('end_date')->nullable()->after('start_date');
            $table->enum('capex_or_opex', ['capex', 'opex'])->nullable()->after('end_date');
            $table->string('no_rekening')->nullable()->after('capex_or_opex');
            $table->decimal('biaya', 15, 2)->nullable()->after('no_rekening');
            $table->string('vendor')->nullable()->after('biaya');
            $table->foreignId('verified_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete()->after('status');
            $table->foreignId('approved_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete()->after('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'start_date',
                'end_date',
                'capex_or_opex',
                'no_rekening',
                'biaya',
                'vendor',
                'verified_by',
                'approved_by',
            ]);
        });
    }
};
