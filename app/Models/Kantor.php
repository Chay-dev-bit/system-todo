<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $table = 'kantor';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'nama',
        'alamat',
        'kota',
        'telp',
        'email',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by',
        'kantor_induk',
    ];
}