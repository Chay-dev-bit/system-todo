<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete orphaned records (task_histories with non-existent task_id)
        DB::statement('DELETE FROM task_histories WHERE task_id NOT IN (SELECT id FROM tasks)');
        
        Schema::table('task_histories', function (Blueprint $table) {
            // Add foreign key constraint if it doesn't exist
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_histories', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
        });
    }
};
