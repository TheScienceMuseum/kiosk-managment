<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Package
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Kiosk[] $kiosks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PackageVersion[] $versions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Package extends Model
{
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kiosks()
    {
        return $this->hasMany(Kiosk::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function versions()
    {
        return $this->hasMany(PackageVersion::class);
    }
}
