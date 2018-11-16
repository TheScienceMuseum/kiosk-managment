<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PackageIndexRequest;
use App\Http\Requests\PackageStoreRequest;
use App\Package;

class PackageController extends Controller
{
    public function index(PackageIndexRequest $request)
    {
        $packages = app('App\Http\Controllers\Api\PackageController')->index($request);

        return view('admin.packages.index', [
            'packages' => $packages,
            'filters' => (object) [

            ]
        ]);
    }

    public function create(\Request $request)
    {
        return view('admin.packages.create');
    }

    public function store(PackageStoreRequest $request)
    {
        $package = app('App\Http\Controllers\Api\PackageController')->store($request);

        return redirect(route('admin.packages.show', [$package]));
    }

    public function show(\Request $request, Package $package)
    {
        $versionsByStatus = $package->versions->mapToGroups(function ($version) {
            return [$version['status'] => $version];
        });

        return view('admin.packages.show', compact('package', 'versionsByStatus'));
    }
}
