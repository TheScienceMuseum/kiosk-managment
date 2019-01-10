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
 * @property int|null $assigned_package_version_id
 * @property \Illuminate\Support\Carbon|null $last_seen_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $manually_set_at
 * @property-read \App\PackageVersion|null $assigned_package_version
 * @property-read mixed $currently_running_package
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\KioskLog[] $logs
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereAssetTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereAssignedPackageVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereClientVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereCurrentPackage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereManuallySetAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereName($value)
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
        'last_seen_at',
        'client_version',
        'current_package',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_seen_at',
        'manually_set_at',
    ];

    static public function allLocations()
    {
        return array_pluck(
            Kiosk::whereNotNull('location')
                ->groupBy(['location'])
                ->get(['location']),
            'location'
        );
    }

    public function getCurrentPackageAttribute()
    {
        return (
            empty($this->attributes['current_package']) ||
            $this->attributes['current_package'] === '@'
        ) ? null : $this->attributes['current_package'];
    }

    public function getCurrentlyRunningPackageAttribute()
    {
        if ($this->current_package) {
            $packageData = explode('@', $this->current_package);
            $packageName = $packageData[0];
            $packageVersion = $packageData[1];

            $foundVersion = PackageVersion::whereVersion($packageVersion)->whereHas('package', function ($query) use ($packageName) {
                $query->where('name', '=', $packageName);
            })->first();

            if ($foundVersion) {
                $packageName = $foundVersion->package->name;
                $packageVersion = $foundVersion->version;
            }

            return $packageName . ' version ' . $packageVersion;
        }

        return null;
    }

    public function getCurrentPackageVersionAttribute() {
        if ($this->current_package) {
            $packageData = explode('@', $this->current_package);
            $packageName = $packageData[0];
            $packageVersion = $packageData[1];

            return PackageVersion::whereVersion($packageVersion)->whereHas('package', function ($query) use ($packageName) {
                $query->where('name', '=', $packageName);
            })->first();
        }

        return null;
    }

    public function assigned_package_version()
    {
        return $this->belongsTo(PackageVersion::class, 'assigned_package_version_id');
    }

    public function logs()
    {
        return $this->hasMany(KioskLog::class);
    }
}
