<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;
    protected $fillable = [
        'siswa_id',
        'nama',
        'alamat',
        'pekerjaan',
        'no_telepon',
        'hubungan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
