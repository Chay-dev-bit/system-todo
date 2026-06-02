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
        $tasks = $this->tasks()
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalTasks = $tasks->count();

        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = $tasks
            ->where('status', 'approved')
            ->count();

        return round(($completedTasks / $totalTasks) * 100);
    }

    // mengecek apakag semua task project sudah approved
    public function isTaskCompleted()
    {
        // task aktif saja
        $tasks = $this->tasks()
            ->where('status', '!=', 'cancelled');

        $totalTasks = $tasks->count();

        // jika tidak ada task aktif
        if ($totalTasks === 0) {

            return false;
        }

        // semua task aktif harus approved
        return $tasks
            ->where('status', 'approved')
            ->count() === $totalTasks;
    }

    // digunakan untuk update final approval project
    public function updateApprovalStatus()
    {
        // jika semua task sudah approved, maka project dianggap completed
        // if ($this->isTaskCompleted()) {

        //     $this->approval_status = 'verified';

        // } else {
        //     // jika belum semua task approved, maka project dianggap masih dalam progress
        //     if (
        //         in_array($this->approval_status, ['verified', 'completed'])
        //     ) {

        //         $this->approval_status = 'rejected';

        //     } else {

        //         $this->approval_status = 'progress';
        //     }
        // }
        $this->save();
    }

    // mengecek apakah final project sudah completed 
    public function isCompleted()
    {
        return $this->approval_status === 'completed';
    }

    // task hanya bisa dibuka jika project sudah approved
    public function isTaskAvailable()
    {
        return $this->status === 'approved';
    }

    // project hanya bisa diverifikasi jika project belum dibatalkan
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

}
