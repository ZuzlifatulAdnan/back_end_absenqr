<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'jenis_kelamin',
        'no_telepon',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
