<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    protected $fillable = [
        'task_id',
        'old_status',
        'new_status',
        'activity',
        'action_type',
        'notes',
        'file_path',
        'changed_by',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function changer()
    {
        return $this->belongsTo(
            Pengguna::class,
            'changed_by',
            'nip'
        );
    }
}