<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kantor_id',
        'dok_bidang',
        'dok_bidang_sub',
        'dok_jenis',
        'dok_tahun',
        'dok_no',
        'unit_id',
        'id',
        'nama_jabatan',
        'tingkat',
        'kls_jab',
        'atasan_bid',
        'atasan_jab',
        'created_by',
        'created_date',
        'modified_by',
        'modified_date',
        'approved_by',
        'approved_date',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}