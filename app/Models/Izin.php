<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
     use HasFactory;
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tanggal_izin',
        'alasan',
        'bukti_surat',
    ];
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
