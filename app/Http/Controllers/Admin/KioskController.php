<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\KioskIndexRequest;
use App\Http\Requests\KioskShowRequest;
use App\Http\Requests\KioskUpdateRequest;
use App\Kiosk;

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

        return view('admin.kiosks.show', [
            'kiosk' => $kiosk,
        ]);
    }

    public function update(KioskUpdateRequest $request, Kiosk $kiosk)
    {
        app('App\Http\Controllers\Api\KioskController')->update($request, $kiosk);

        return redirect()
            ->route('admin.kiosk.show', [$kiosk])
            ->with(['status' => __('kiosk.action.update.success')]);
    }
}
