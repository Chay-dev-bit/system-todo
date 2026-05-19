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
        Schema::create('projects', function (Blueprint $table) {

            // ID PROJECT
            $table->id();

            // NAMA PROJECT
            $table->string('project_name');

            // DESKRIPSI
            $table->text('description')->nullable();

            // PIC PROJECT
            $table->foreignId('pic_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // DEADLINE PROJECT
            $table->date('deadline')->nullable();

            // STATUS PROJECT
            $table->enum('status', [
                'pending',
                'ongoing',
                'completed',
                'cancelled'
            ])->default('pending');

            // siapa membuat
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // kapan dibuat
            $table->timestamp('created_at')
                ->useCurrent();

            // siapa mengubah
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // kapan diubah
            $table->timestamp('updated_at')
                ->nullable()
                ->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project');
    }
};
