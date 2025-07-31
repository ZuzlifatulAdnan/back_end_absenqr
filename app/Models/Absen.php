<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
     use HasFactory;
    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'absenqr_id',
        'waktu',
        'latitude',
        'longitude',
        'status',
    ];
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
    public function absenqr()
    {
        return $this->belongsTo(Absen_Qr::class);
    }
}
