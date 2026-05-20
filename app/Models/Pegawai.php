<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $primaryKey = 'nip';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'nip',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jen_kel',
        'alamat',
        'agama',
        'status_perkawinan',
        'no_telp',
        'al_email',
        'aktif',
        'kantor_id',
        'jabatan_unit_id',
        'jabatan_id',
        'created_by',
        'created_date',
        'modified_by',
        'modified_date',
        'approved_by',
        'approved_date',
    ];
}