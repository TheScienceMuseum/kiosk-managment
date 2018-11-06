<?php

namespace App\Http\Controllers\Api;

use App\Filters\UnregisteredKioskFilter;
use App\Http\Requests\KioskAssignPackageRequest;
use App\Http\Requests\KioskDestroyRequest;
use App\Http\Requests\KioskHealthCheckRequest;
use App\Http\Requests\KioskIndexRequest;
use App\Http\Requests\KioskPackageDownloadRequest;
use App\Http\Requests\KioskPackageUpdateRequest;
use App\Http\Requests\KioskRegisterRequest;
use App\Http\Requests\KioskShowLogsRequest;
use App\Http\Requests\KioskShowRequest;
use App\Http\Requests\KioskUpdateRequest;
use App\Http\Resources\KioskLogsResource;
use App\Http\Resources\KioskResource;
use App\Kiosk;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class KioskController
 * @package App\Http\Controllers\Api
 * @resource Kiosk
 */
class KioskController extends Controller
{
    /**
     * @param KioskIndexRequest $request
     * @return mixed
     */
    public function index(KioskIndexRequest $request) : ResourceCollection
    {
        $kiosks = QueryBuilder::for(Kiosk::class)
            ->allowedFilters([
                'name',
                'location',
                'asset_tag',
                'client_version',
                'current_package',
                Filter::custom('registered', UnregisteredKioskFilter::class)
            ])
            ->jsonPaginate()
        ;

        return KioskResource::collection($kiosks);
    }

    /**
     * @param KioskShowRequest $request
     * @param Kiosk $kiosk
     * @return KioskResource
     */
    public function show(KioskShowRequest $request, Kiosk $kiosk) : KioskResource
    {
        return new KioskResource($kiosk);
    }

    /**
     * @param KioskShowLogsRequest $request
     * @param Kiosk $kiosk
     * @return KioskLogsResource
     */
    public function showLogs(KioskShowLogsRequest $request, Kiosk $kiosk) : KioskLogsResource
    {
        return new KioskLogsResource($kiosk);
    }

    /**
     * @param KioskUpdateRequest $request
     * @param Kiosk $kiosk
     * @return KioskResource
     */
    public function update(KioskUpdateRequest $request, Kiosk $kiosk) : KioskResource
    {
        $kiosk->update([
            'name' => $request->input('name'),
            'location' => $request->input('location'),
            'asset_tag' => $request->input('asset_tag'),
        ]);

        return new KioskResource($kiosk);
    }

    /**
     * @param KioskDestroyRequest $request
     * @param Kiosk $kiosk
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(KioskDestroyRequest $request, Kiosk $kiosk)
    {
        $kiosk->delete();

        return response('', 204);
    }

    /**
     * @param KioskHealthCheckRequest $request
     * @return KioskResource
     */
    public function healthCheck(KioskHealthCheckRequest $request) : KioskResource
    {
        $kiosk = $this->getKioskFromRequest($request);

        $kiosk->last_seen_at = now();
        $kiosk->client_version = $request->input('client.version');
        $kiosk->current_package = $request->input('package.name') . '@' . $request->input('package.version');
        $kiosk->save();

        if ($request->input('logs')) {
            foreach ($request->input('logs') as $logEntry) {
                if ($kiosk->logs()->whereTimestamp($logEntry['timestamp'])->get()->count() === 0) {
                    $kiosk->logs()->create([
                        'level' => $logEntry['level'],
                        'message' => $logEntry['message'],
                        'timestamp' => $logEntry['timestamp'],
                    ]);
                }
            }
        }

        return new KioskResource($kiosk);
    }

    /**
     * @param KioskRegisterRequest $request
     * @return KioskResource
     */
    public function register(KioskRegisterRequest $request) : KioskResource
    {
        $kiosk = Kiosk::create([
            'identifier' => $request->input('identifier'),
            'last_seen_at' => now(),
            'client_version' => $request->input('client.version'),
        ]);

        return new KioskResource($kiosk);
    }

    public function download(KioskPackageDownloadRequest $request)
    {
        $kiosk = $this->getKioskFromRequest($request);

        if ($kiosk->package->current_version) {
            return response()->download($kiosk->package->current_version->path);
        }

        return abort(404);
    }

    /**
     * @param KioskAssignPackageRequest $request
     * @param Kiosk $kiosk
     * @return KioskResource
     */
    public function assignPackage(KioskAssignPackageRequest $request, Kiosk $kiosk, Package $package) : KioskResource
    {
        $kiosk->package()->associate($package);

        return new KioskResource($kiosk);
    }

    /**
     * @param Request $request
     * @return Kiosk
     */
    private function getKioskFromRequest(Request $request) : Kiosk
    {
        $kiosk = Kiosk::whereIdentifier($request->input('identifier'))
            ->firstOrFail()
        ;

        $kiosk->last_seen_at = now();
        $kiosk->save();

        return $kiosk;
    }
}
