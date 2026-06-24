<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimWaktu extends Model
{
    use HasFactory;

    protected $table = 'dim_waktu';
    protected $primaryKey = 'waktu_key';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tanggal',
        'hari',
        'bulan',
        'tahun',
        'nama_hari',
        'nama_bulan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
