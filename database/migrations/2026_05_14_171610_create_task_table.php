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
        Schema::create('tasks', function (Blueprint $table) 
        {
            $table->id();

            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // judul task
            $table->string('title');

            // deskripsi task
            $table->text('description')->nullable();

            // penerima tugas
            $table->foreignId('assigned_to')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('deadline')->nullable();

            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'urgent'
            ])->default('medium');

            $table->enum('status', [
                'pending',
                'progress',
                'submitted',
                'revision',
                'done'
            ])->default('pending');

            $table->integer('progress')
                ->default(0);

            // nama file
            $table->string('attachment')->nullable();

            // revisi dari manager/asmen
            $table->text('revision_notes')->nullable();

            // kapan task disubmit
            $table->timestamp('submitted_at')
                ->nullable();

            // siapa membuat
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // kapan dibuat
            $table->timestamp('created_at')
                ->useCurrent();

            // siapa update
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // kapan diupdate
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
        Schema::dropIfExists('task');
    }
};
