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
        'verified_by',
        'verified_at',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'deadline' => 'date',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'created_by', 'nip');
    }

    public function assignee()
    {
        return $this->belongsTo(Pengguna::class, 'assigned_to', 'nip');
    }

    public function updater()
    {
        return $this->belongsTo(Pengguna::class, 'updated_by', 'nip');
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

    public function verifier()
    {
        return $this->belongsTo(Pengguna::class, 'verified_by', 'nip');
    }

    public function approver()
    {
        return $this->belongsTo(Pengguna::class, 'approved_by', 'nip');
    }
}