<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class KioskPackageVersionResource extends JsonResource
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
            'name' => $this->package->name,
            'slug' => Str::kebab($this->package->name),
            'version' => (int) $this->version,
            'path' => route('api.kiosk.package.download', [$this->package, $this]),
            'package' => new KioskPackageResource($this->package),
            'status' => $this->status,
        ];
    }
}
