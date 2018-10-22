<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KioskPackageResource extends JsonResource
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
            'name' => $this->name,
            'versions' => PackageVersionResource::collection($this->versions),
            'current_version' => new PackageVersionResource($this->versions()->orderByDesc('version')->first()),
        ];
    }
}
