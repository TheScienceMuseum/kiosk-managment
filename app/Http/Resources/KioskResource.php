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
            'current_package' => $this->current_package === '@' ? null : $this->current_package,
            'last_seen_at' => $this->last_seen_at ? $this->last_seen_at->toAtomString() : null,
            'package' => new PackageResource($this->package),
            'path' => route('api.kiosk.show', $this),
        ];
    }
}
