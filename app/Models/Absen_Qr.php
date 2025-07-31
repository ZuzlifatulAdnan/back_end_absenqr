<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen_Qr extends Model
{
     use HasFactory;
    protected $fillable = [
        'jadwal_id',
        'tanggal_absen',
        'token_qr',
        'expired_at',
    ];
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
