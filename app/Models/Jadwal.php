<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i:s',
        'jam_selesai' => 'datetime:H:i:s',
    ];

    protected $fillable = [
        'user_id',
        'kode_karakter',
        'nama_jadwal',
        'deskripsi',
        'opening',
        'narasumber',
        'moderator',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'is_shared',
    ];
}
