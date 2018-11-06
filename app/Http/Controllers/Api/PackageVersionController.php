<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PackageVersionResource;
use App\Package;
use App\PackageVersion;
use Illuminate\Http\Request;

class PackageVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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
     * @param  \App\PackageVersion  $packageVersion
     * @return \Illuminate\Http\Response
     */
    public function show(PackageVersion $packageVersion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PackageVersion  $packageVersion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PackageVersion $packageVersion)
    {
        //
    }
}
