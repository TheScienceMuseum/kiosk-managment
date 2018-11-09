<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\KioskAssignPackageRequest;
use App\Http\Requests\KioskIndexRequest;
use App\Http\Requests\KioskShowRequest;
use App\Http\Requests\KioskUpdateRequest;
use App\Kiosk;
use App\PackageVersion;

class KioskController extends Controller
{
    public function index(KioskIndexRequest $request)
    {
        $kiosks = app('App\Http\Controllers\Api\KioskController')->index($request);

        return view('admin.kiosks.index', [
            'kiosks' => $kiosks,
            'filters' => (object) [
                'location' => Kiosk::allLocations(),
            ]
        ]);
    }

    public function show(KioskShowRequest $request, Kiosk $kiosk)
    {
        $kiosk = app('App\Http\Controllers\Api\KioskController')->show($request, $kiosk);
        $approvedPackageVersions = PackageVersion::whereStatus('approved')->get()->mapToGroups(function (PackageVersion $packageVersion) {
            return [$packageVersion->package->name => $packageVersion];
        });

        return view('admin.kiosks.show', compact('kiosk', 'approvedPackageVersions'));
    }

    public function update(KioskUpdateRequest $request, Kiosk $kiosk)
    {
        $packageVersion = PackageVersion::whereStatus('approved')->find($request->get('assigned_package_version'));

        if ($packageVersion) {
            $kiosk->assigned_package_version()->associate($packageVersion);
        } else {
            $kiosk->assigned_package_version()->dissociate();
        }
        $kiosk->save();

        app('App\Http\Controllers\Api\KioskController')
            ->update($request, $kiosk);

        return redirect()
            ->route('admin.kiosks.show', [$kiosk])
            ->with(['status' => __('kiosk.action.update.success')]);
    }
}
