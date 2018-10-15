<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageVersionResource extends JsonResource
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
            'version' => $this->version,
            'package_path' => $this->path,
        ];
    }
}
