<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactAbsensi extends Model
{
    use HasFactory;

    protected $table = 'fact_absensi';

    protected $fillable = [
        'pegawai_key',
        'divisi_key',
        'jabatan_key',
        'waktu_key',
        'total_hadir',
        'total_telat',
        'total_izin',
        'total_sakit',
        'total_alfa',
    ];
}
