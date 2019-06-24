<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * App\CustomPage
 *
 * @property int $id
 * @property string $name
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomPage whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomPage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomPage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomPage extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $casts = [
        'data' => 'json',
    ];
}
