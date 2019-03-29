<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}
