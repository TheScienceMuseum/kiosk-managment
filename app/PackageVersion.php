<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * App\PackageVersion
 *
 * @property int $id
 * @property int $package_id
 * @property int $version
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed|null $data
 * @property int $progress
 * @property-read mixed $archive_path
 * @property-read mixed $archive_path_exists
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Kiosk[] $kiosks
 * @property-read \App\Package $package
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereVersion($value)
 * @mixin \Eloquent
 */
class PackageVersion extends Model
{
    protected $fillable = [
        'version',
        'status',
        'data',
        'progress',
    ];

//    When moving to fully api driven package creation, uncomment this.
//    protected $casts = [
//        'data' => 'json',
//    ];

    /**
     * @param $value
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setStatusAttribute($value)
    {
        Validator::make([
            'status' => $value,
        ], [
            'status' => 'required|in:draft,pending,approved,failed',
        ])->validate();

        $this->attributes['status'] = $value;
    }

    public function getArchivePathAttribute()
    {
        return \Storage::disk(config('filesystems.cloud'))
            ->path('public/packages/'.$this->package->name . '_' . $this->version . '.package');
    }

    public function getArchivePathExistsAttribute()
    {
        return file_exists($this->archive_path);
    }

    public function kiosks()
    {
        return $this->hasMany(Kiosk::class, 'assigned_package_version_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
