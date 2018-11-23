<?php

namespace App\Http\Controllers;

class DownloadController extends Controller
{
    /**
     * @param $os
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|void
     */
    public function downloadKioskClient($os)
    {
        if (!in_array($os, ['macos', 'win'])) {
            return abort(404, 'There is no client for ' . $os);
        }

        $filePath = 'kiosk-client-' . $os . '-' . config('kiosk.client-version') . '-' . config('app.env') . '.zip';

        if (!\Storage::disk(config('filesystems.builds'))->exists($filePath)) {
            return abort(404, 'We don\'t have a copy of the client at version ' . config('kiosk.client-version') . ' for the platform ' . $os);
        }

        return \Storage::disk(config('filesystems.builds'))->download($filePath);
    }
}
