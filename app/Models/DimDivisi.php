<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimDivisi extends Model
{
    use HasFactory;

    protected $table = 'dim_divisi';
    protected $primaryKey = 'divisi_key';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'divisi_id',
        'nama_divisi',
    ];
}
