<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use App\Enums\LateStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'pegawai_id', 'tanggal', 'jam_masuk', 'jam_keluar', 'status_kehadiran', 'status_keterlambatan', 'menit_terlambat', 'keterangan', 'bukti_file'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status_kehadiran' => AttendanceStatus::class,
        'status_keterlambatan' => LateStatus::class,
        'menit_terlambat' => 'integer',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
