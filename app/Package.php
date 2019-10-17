<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Package
 *
 * @property int $id
 * @property string $name
 * @property string $aspect_ratio
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Kiosk[] $kiosks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PackageVersion[] $versions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package whereAspectRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Package whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Package extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,
        \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = ['name', 'aspect_ratio'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function kiosks()
    {
        return $this->hasManyDeep(
            Kiosk::class,
            [PackageVersion::class],
            ['package_id', 'assigned_package_version_id']
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function versions()
    {
        return $this->hasMany(PackageVersion::class);
    }

    /**
     * Return a file friendy name for packages
     */
    public function getFileFriendlyName() {
        $string = str_replace(' ', '-', $this->name); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public function createVersion()
    {
        /** @var PackageVersion $previousVersion */
        $previousVersion = $this->versions->last();

        if ($previousVersion) {
            $newVersion = $previousVersion->createNewVersion();
        } else {
            $newVersion = $this->versions()->create([
                'version' => 1,
                'status' => 'draft',
                'progress' => 0,
                'data' => [
                    'main' => 'index.html',
                    'requirements' => [
                        'client_version' => '0.0.1',
                    ],
                    'content' => [
                        'titles' => [
                            'type' => 'text',
                            'image' => NULL,
                            'title' => $this->name,
                            'gallery' => 1,
                            'attractor' => NULL,
                            'idleTimeout' => 30,
                        ],
                        'contents' => [],
                    ],
                ],
            ]);
        }

        return $newVersion;
    }
}
