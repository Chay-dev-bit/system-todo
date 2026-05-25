<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'kode_project',
        'project_name',
        'description',
        'start_date',
        'end_date',
        'capex_or_opex',
        'no_rekening',
        'biaya',
        'vendor',
        'pic_id',
        'asmen_id',
        'manajer_id',
        'status',
        'approval_status',
        'verified_by',
        'verified_at',
        'approved_by',
        'approved_at',
        'rejection_note',
        'rejected_by',
        'rejected_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'created_by', 'nip');
    }

    public function pic()
    {
        return $this->belongsTo(Pengguna::class, 'pic_id', 'nip');
    }

    public function asmen()
    {
        return $this->belongsTo(Pengguna::class, 'asmen_id', 'nip');
    }

    public function manajer()
    {
        return $this->belongsTo(Pengguna::class, 'manajer_id', 'nip');
    }

    public function updater()
    {
        return $this->belongsTo(Pengguna::class, 'updated_by', 'nip');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function verifier()
    {
        return $this->belongsTo(Pengguna::class, 'verified_by', 'nip');
    }

    public function approver()
    {
        return $this->belongsTo(Pengguna::class, 'approved_by', 'nip');
    }

    public function rejector()
    {
        return $this->belongsTo(Pengguna::class, 'rejected_by', 'nip');
    }

    public function getProgressPercentageAttribute()
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) {
            return 0;
        }
        $completedTasks = $this->tasks()->where('status', 'approved')->count();
        return round(($completedTasks / $totalTasks) * 100);
    }

    public function updateStatus()
    {
        $totalTasks = $this->tasks()->count();
        
        if ($totalTasks === 0) {
            $this->update(['status' => 'pending']);
            return;
        }

        $approvedTasks = $this->tasks()->where('status', 'approved')->count();
        
        if ($approvedTasks === $totalTasks) {
            $this->update(['status' => 'completed']);
        } else {
            $this->update(['status' => 'ongoing']);
        }
    }
}
