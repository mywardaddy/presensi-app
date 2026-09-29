<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id',
        'date',
        'entry_time',
        'out_time',
        'description',
        'notes',
        'activity',
        'latitude',
        'longitude',
        'picture',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id','user_id');
    }
}
