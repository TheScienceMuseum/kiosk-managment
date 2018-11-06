<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PackageIndexRequest;

class PackageController extends Controller
{
    public function packageIndex(PackageIndexRequest $request)
    {
        $packages = app('App\Http\Controllers\Api\PackageController')->index($request);

        return view('admin.package.index', [
            'packages' => $packages,
            'filters' => (object) [

            ]
        ]);
    }
}
