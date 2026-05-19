<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskApproval extends Model
{
    protected $table = 'task_approvals';

    protected $fillable = [
        'task_id',
        'approved_by',
        'approval_level',
        'status',
        'notes',
        'approved_at',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}