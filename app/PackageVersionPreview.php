<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PackageVersionPreview
 *
 * @property int $id
 * @property int $package_version_id
 * @property string|null $preview_path
 * @property bool $build_complete
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\PackageVersion $package_version
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersionPreview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersionPreview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersionPreview query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersionPreview whereBuildComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersionPreview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersionPreview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersionPreview wherePackageVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersionPreview wherePreviewPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersionPreview whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PackageVersionPreview extends Model
{
    protected $fillable = ['preview_path', 'build_complete'];

    protected $casts = [
        'build_complete' => 'bool',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package_version()
    {
        return $this->belongsTo(PackageVersion::class);
    }
}
