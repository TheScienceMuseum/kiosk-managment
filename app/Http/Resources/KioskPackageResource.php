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
        $current_version = $this->versions()->orderByDesc('version')->first();

        return [
            'name' => $this->name,
            'version' => $current_version->version,
        ];
    }
}
