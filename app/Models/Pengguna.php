<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';

    protected $primaryKey = 'nip';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kantor_id',
        'nip',
        'nama_lengkap',
        'nama_awal',
        'nama_akhir',
        'nama_pemakai',
        'email',
        'no_wa',
        'password',
        'role_id',
        'aktif',
        'last_login',
        'created_by',
        'created_date',
        'modified_by',
        'modified_date',
        'approved_by',
        'approved_date',
        'remember_token',
    ];
    protected $hidden = [
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function pegawai()
    {
        return $this->belongsTo(
            Pegawai::class,
            'nip',
            'nip'
        );
    }
}