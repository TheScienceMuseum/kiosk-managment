<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\PackageVersionShowRequest;
use App\Http\Requests\PackageVersionStoreRequest;
use App\Http\Requests\PackageVersionUpdateRequest;
use App\Package;
use App\PackageVersion;

class PackageVersionController extends Controller
{
    public function store(PackageVersionStoreRequest $request, Package $package)
    {
        $version = app('App\Http\Controllers\Api\PackageVersionController')->store($request, $package);

        return redirect(route('admin.packages.versions.show', [$package, $version]));
    }

    public function show(PackageVersionShowRequest $request, Package $package, PackageVersion $packageVersion)
    {
        $version = app('App\Http\Controllers\Api\PackageVersionController')->show($request, $package, $packageVersion);

        return view('admin.packages.show_version', compact('version'));
    }

    public function update(PackageVersionUpdateRequest $request, Package $package, PackageVersion $packageVersion)
    {
        $version = app('App\Http\Controllers\Api\PackageVersionController')->update($request, $package, $packageVersion);

        return view('admin.packages.show_version', compact('version'));
    }
}
