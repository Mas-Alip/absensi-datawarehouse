<?php

namespace App\Models;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'nip', 'nama', 'jenis_kelamin', 'alamat', 'no_hp', 'tanggal_masuk', 'status', 'divisi_id', 'jabatan_id', 'user_id'
    ];

    protected $casts = [
        'jenis_kelamin' => Gender::class,
        'status' => EmployeeStatus::class,
        'tanggal_masuk' => 'date',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'pegawai_id');
    }
}
