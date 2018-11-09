<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KioskResource extends JsonResource
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
            'location' => $this->location,
            'asset_tag' => $this->asset_tag,
            'identifier' => $this->identifier,
            'client_version' => $this->client_version,
            'current_package' => $this->current_package,
            'manually_set' => $this->manually_set_at ? $this->manually_set_at->timestamp : null,
            'last_seen_at' => $this->last_seen_at ? $this->last_seen_at->toAtomString() : null,
            'package' => new KioskPackageVersionResource($this->assigned_package_version),
            'path' => route('api.kiosk.show', $this),
        ];
    }
}
