<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    //
    protected $fillable = ['pengirim_id', 'user_id', 'notifikasi'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
}
