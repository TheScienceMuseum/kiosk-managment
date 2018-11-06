<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'name' => $this->package->name,
            'version' => (int) $this->version,
            'path' => route('api.kiosk.package.download', [$this->package, $this]),
        ];
    }
}
