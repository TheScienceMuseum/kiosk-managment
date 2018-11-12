<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PackageVersionShowRequest;
use App\Http\Requests\PackageVersionUpdateRequest;
use App\Http\Resources\PackageVersionResource;
use App\Package;
use App\PackageVersion;
use Illuminate\Http\Request;

class PackageVersionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Package $package
     * @return PackageVersionResource
     */
    public function store(Request $request, Package $package) : PackageVersionResource
    {
        $packageVersion = $package->versions()->create([
            'version' => $package->versions()->count() === 0 ? 1 : $package->versions()->count() + 1,
        ]);

        return new PackageVersionResource($packageVersion);
    }

    /**
     * Display the specified resource.
     *
     * @param PackageVersionShowRequest $request
     * @param Package $package
     * @param  \App\PackageVersion $packageVersion
     * @return PackageVersionResource
     */
    public function show(PackageVersionShowRequest $request, Package $package, PackageVersion $packageVersion) : PackageVersionResource
    {
        return new PackageVersionResource($packageVersion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PackageVersionUpdateRequest $request
     * @param Package $package
     * @param  \App\PackageVersion $packageVersion
     * @return PackageVersionResource
     */
    public function update(PackageVersionUpdateRequest $request, Package $package, PackageVersion $packageVersion) : PackageVersionResource
    {
        $packageVersion->update([
            'data' => $request->input('data'),
            'status' => $request->input('status'),
        ]);

        return new PackageVersionResource($packageVersion);
    }
}
