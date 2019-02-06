<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageVersionAssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'file_name' => $this->file_name,
            'mime_type' => $this->mime_type,
            'url_thumb' => config('filesystems.disks.' . config('filesystems.assets') . '.driver') === 's3' ?
                $this->getTemporaryUrl('thumb') :
                $this->getUrl('thumb'),
            'url_original' => config('filesystems.disks.' . config('filesystems.assets') . '.driver') === 's3' ?
                $this->getTemporaryUrl() :
                $this->getUrl(),
        ];
    }
}
