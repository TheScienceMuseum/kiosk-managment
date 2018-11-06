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
 * @property int $approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $file
 * @property-read mixed $file_name
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
        'status',
    ];

    /**
     * @param $value
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setStatusAttribute($value)
    {
        Validator::make([
            'status' => $value,
        ], [
            'title' => 'required|in:draft,pending,approved',
        ])->validate();

        $this->attributes['status'] = $value;
    }

    public function kiosks()
    {
        return $this->hasMany(Kiosk::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
