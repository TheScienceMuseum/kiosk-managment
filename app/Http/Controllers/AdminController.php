<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\KioskController;
use App\Http\Requests\KioskIndexRequest;
use App\Http\Requests\KioskShowRequest;
use App\Kiosk;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
//        return view('admin.users');
    }

    public function kioskIndex(Request $request)
    {
        $kiosks = app('App\Http\Controllers\Api\KioskController')->index(new KioskIndexRequest());

        return view('admin.kiosks.index', [
            'kiosks' => $kiosks,
            'filters' => (object) [
                'location' => array_pluck(Kiosk::whereNotNull('location')->groupBy(['location'])->get(['location']), 'location'),
            ]
        ]);
    }

    public function kioskShow(Request $request, Kiosk $kiosk)
    {
        $kiosk = app('App\Http\Controllers\Api\KioskController')->show(new KioskShowRequest(), $kiosk);

        return $kiosk;
    }
}
