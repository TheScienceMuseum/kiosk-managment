<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\KioskLog
 *
 * @property-read \App\Kiosk $kiosk
 * @mixin \Eloquent
 */
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
