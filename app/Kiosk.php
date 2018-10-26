<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Kiosk
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $location
 * @property string|null $asset_tag
 * @property string $identifier
 * @property string|null $client_version
 * @property string|null $current_package
 * @property int|null $package_id
 * @property \Illuminate\Support\Carbon|null $last_seen_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Package|null $package
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereAssetTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereClientVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereCurrentPackage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Kiosk extends Model
{
    protected $fillable = [
        'name',
        'location',
        'asset_tag',
        'identifier',
        'client_version',
        'current_package',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_seen_at',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
