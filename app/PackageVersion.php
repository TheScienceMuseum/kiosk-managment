<?php

namespace App;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\TemporaryDirectory\TemporaryDirectory;

/**
 * App\PackageVersion
 *
 * @property int $id
 * @property int $package_id
 * @property int $version
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $data
 * @property int $progress
 * @property-read mixed $archive_path
 * @property-read mixed $archive_path_exists
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Kiosk[] $kiosks
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read \App\Package $package
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PackageVersion query()
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
class PackageVersion extends Model implements HasMedia, Auditable
{
    use \OwenIt\Auditing\Auditable, HasMediaTrait;

    protected $fillable = [
        'version',
        'status',
        'data',
        'progress',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->blur(1)
            ->fit(Manipulations::FIT_CROP, 150, 150);
    }

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

    public function getArchivePathAttribute() : string
    {
        return Str::kebab($this->package->name) . '_' . $this->version . '.package';
    }

    public function getArchivePathExistsAttribute() : bool
    {
        return \Storage::disk(config('filesystems.packages'))
            ->exists($this->archive_path);
    }

    public function kiosks()
    {
        return $this->hasMany(Kiosk::class, 'assigned_package_version_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function createNewVersion(Package $package = null)
    {
        $newVersion = $this->replicate(['media', 'kiosks']);
        $newVersion->version = $package ? $package->versions()->count() + 1 : $this->version + 1;
        $newVersion->status = 'draft';
        $newVersion->progress = 0;
        $newVersion->save();
        $newVersion->package()->associate($package ? $package : $this->package);

        $temporaryDirectory = (new TemporaryDirectory())->create();

        foreach($this->media as $media) {
            $tempPath = $temporaryDirectory->path($media->file_name);
            $diskConfig = config("filesystems.disks.{$media->disk}");
            $disk = Storage::disk($media->disk);
            $path = $media->getPath();

            if (!empty($diskConfig['root'])) {
                $path = str_replace($diskConfig['root'] . '/', '', $path);
            }

            if (!$disk->exists($path)) {
                continue;
            }

            try {
                file_put_contents($tempPath, $disk->get($path));
            } catch (FileNotFoundException $e) {
                continue;
            }

            $clonedMedia = $newVersion->addMedia($tempPath)->toMediaCollection();

            // Replace asset ids in data with new asset id.
            $data = json_encode($newVersion->data);
            $data = str_replace('"assetId":'.$media->id, '"assetId":'.$clonedMedia->id, $data);
            $newVersion->data = json_decode($data);
            $newVersion->save();
        }

        $temporaryDirectory->delete();

        $newVersion->save();
        return $newVersion;
    }
}
