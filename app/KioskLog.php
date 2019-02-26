<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\KioskLog
 *
 * @property int $id
 * @property int $kiosk_id
 * @property string $level
 * @property string $message
 * @property string $timestamp
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Kiosk $kiosk
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskLog whereKioskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskLog whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskLog whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class KioskLog extends Model
{
    protected $fillable = [
        'level',
        'message',
        'timestamp',
    ];

    public function kiosk()
    {
        return $this->belongsTo(Kiosk::class);
    }

    public function seen_by_user()
    {
        return $this->belongsTo(User::class);
    }
}
