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
        'status',
        'verified_by',
        'approved_by',
        'created_by',
        'updated_by',
    ];

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'created_by', 'nip');
    }

    public function pic()
    {
        return $this->belongsTo(Pengguna::class, 'pic_id', 'nip');
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
}