<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimPegawai extends Model
{
    use HasFactory;

    protected $table = 'dim_pegawai';
    protected $primaryKey = 'pegawai_key';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'pegawai_id',
        'nip',
        'nama',
        'jenis_kelamin',
        'status',
        'tanggal_masuk',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];
}
