<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SiteIndexRequest;
use App\Http\Resources\SiteResource;
use App\Site;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(SiteIndexRequest $request): JsonResource
    {
        return SiteResource::collection(Site::all());
    }
}
