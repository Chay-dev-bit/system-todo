<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

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
        'id',
        'unit_name',
        'tingkat',
        'created_by',
        'created_date',
        'modified_by',
        'modified_date',
        'approved_by',
        'approved_date',
    ];

    public function kantor()
    {
        return $this->belongsTo(Kantor::class, 'kantor_id', 'id');
    }
}