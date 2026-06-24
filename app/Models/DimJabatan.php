<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimJabatan extends Model
{
    use HasFactory;

    protected $table = 'dim_jabatan';
    protected $primaryKey = 'jabatan_key';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'jabatan_id',
        'nama_jabatan',
    ];
}
