<?php

namespace App\Http\Resources;

use App\PackageVersion;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $latest_approved_version = $this->versions->filter(function (PackageVersion $packageVersion) {
            return $packageVersion->status === 'approved';
        })->sortByDesc('version')->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => Str::kebab($this->name),
            'aspect_ratio' => $this->aspect_ratio,
            'versions' => PackageVersionResource::collection($this->versions()->get()),
            'kiosks' => KioskResource::collection($this->kiosks),
            'latest_approved_version' => $latest_approved_version ? $latest_approved_version->version : null,
        ];
    }
}
