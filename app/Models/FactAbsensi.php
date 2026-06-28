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
        'total_smart_hadir',
        'total_telat',
        'selfie_path',
        'latitude',
        'longitude',
        'google_maps_url',
        'total_izin',
        'total_sakit',
        'total_alfa',
    ];
}
