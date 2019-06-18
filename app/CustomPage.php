<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class CustomPage extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $casts = [
        'data' => 'json',
    ];
}
