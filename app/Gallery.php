<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
