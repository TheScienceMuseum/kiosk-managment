<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Site
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Gallery[] $galleries
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Site newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Site newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Site query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Site whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Site whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Site whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Site whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Site extends Model
{
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function galleries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Gallery::class);
    }
}
