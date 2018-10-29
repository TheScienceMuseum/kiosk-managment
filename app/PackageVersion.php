<?php

namespace App;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;

/**
 * App\PackageVersion
 *
 * @property int $id
 * @property int $package_id
 * @property int $version
 * @property int $approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $path
 * @property-read \App\Package $package
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion whereVersion($value)
 * @mixin \Eloquent
 */
class PackageVersion extends Model
{
    protected $fillable = [
        'version',
        'approved',
    ];

    public function getFileNameAttribute()
    {
        return snake_case($this->package->name) . '_' . $this->version . '.package';
    }

    public function getPathAttribute()
    {
        return storage_path('app/public/packages/' . $this->file_name);
    }

    public function getFileAttribute()
    {
        return \Storage::disk(config('filesystems.cloud'))->get(storage_path('app/public/packages/' . $this->file_name));
//        try {
//        } catch (FileNotFoundException $e) {
//            return null;
//        }
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
