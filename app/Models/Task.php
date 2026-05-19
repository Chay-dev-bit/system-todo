<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'assigned_to',
        'deadline',
        'priority',
        'status',
        'progress',
        'attachment',
        'revision_notes',
        'submitted_at',
        'created_by',
        'updated_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approvals()
    {
        return $this->hasMany(TaskApproval::class);
    }

    public function histories()
    {
        return $this->hasMany(TaskHistory::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}