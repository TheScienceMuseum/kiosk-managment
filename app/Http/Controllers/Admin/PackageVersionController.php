<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\AdminPackageVersionUpdateRequest;
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
        if (!in_array($packageVersion->status, ['draft', 'failed']) && $packageVersion->progress < 100) {
            return redirect(route('admin.packages.show', [$package]))->withErrors('Cannot edit a package that is being built.');
        }

        $version = app('App\Http\Controllers\Api\PackageVersionController')->show($request, $package, $packageVersion);

        return view('admin.packages.show_version', compact('version'));
    }

    public function update(AdminPackageVersionUpdateRequest $request, Package $package, PackageVersion $packageVersion)
    {
        $apiRequest = new PackageVersionUpdateRequest();
        $apiRequest->replace([
            'status' => $request->input('status'),
            'data' => json_decode($request->input('data')),
        ]);

        app('App\Http\Controllers\Api\PackageVersionController')->update($apiRequest, $package, $packageVersion);

        return redirect(route('admin.packages.show', [$package]));
    }

    public function download(\Request $request, Package $package, PackageVersion $packageVersion)
    {
        if ($packageVersion->archive_path_exists) {
            return response()->download($packageVersion->archive_path);
        }

        return abort(404);
    }

    public function approve(\Request $request, Package $package, PackageVersion $packageVersion)
    {
        $packageVersion->update([
            'status' => 'approved',
        ]);

        return redirect(route('admin.packages.show', [$package]));
    }
}
