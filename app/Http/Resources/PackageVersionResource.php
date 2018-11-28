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
            'download' => $this->archive_path_exists ? route('api.kiosk.package.download', [$this->package, $this]) : null,
        ];
    }
}
