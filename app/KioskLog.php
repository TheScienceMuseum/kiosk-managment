<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KioskLog extends Model
{
    protected $fillable = [
        'level',
        'message',
    ];

    public function kiosk()
    {
        return $this->belongsTo(Kiosk::class);
    }
}
