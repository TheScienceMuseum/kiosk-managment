<?php

namespace App\Http\Controllers\Api;

use App\Filters\UnregisteredKioskFilter;
use App\Http\Requests\KioskDestroyRequest;
use App\Http\Requests\KioskHealthCheckRequest;
use App\Http\Requests\KioskIndexRequest;
use App\Http\Requests\KioskPackageUpdateRequest;
use App\Http\Requests\KioskRegisterRequest;
use App\Http\Requests\KioskShowRequest;
use App\Http\Requests\KioskUpdateRequest;
use App\Http\Resources\KioskResource;
use App\Kiosk;
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
    public function index(KioskIndexRequest $request)
    {
        $kiosks = QueryBuilder::for(Kiosk::class)
            ->allowedFilters([
                'name',
                'address',
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
    public function show(KioskShowRequest $request, Kiosk $kiosk)
    {
        return new KioskResource($kiosk);
    }

    /**
     * @param KioskUpdateRequest $request
     * @param Kiosk $kiosk
     * @return KioskResource
     */
    public function update(KioskUpdateRequest $request, Kiosk $kiosk)
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
    public function healthCheck(KioskHealthCheckRequest $request)
    {
        $kiosk = Kiosk::whereIdentifier($request->input('identifier'))
            ->firstOrFail()
        ;

        $kiosk->last_seen_at = now();
        $kiosk->client_version = $request->input('client.version');
        $kiosk->current_package = $request->input('package.name') . '@' . $request->input('package.version');
        $kiosk->save();

        return new KioskResource($kiosk);
    }

    /**
     * @param KioskPackageUpdateRequest $request
     * @param Kiosk $kiosk
     */
    public function packageUpdate(KioskPackageUpdateRequest $request, Kiosk $kiosk)
    {

    }

    /**
     * @param KioskRegisterRequest $request
     * @return KioskResource
     */
    public function register(KioskRegisterRequest $request)
    {
        $kiosk = Kiosk::create([
            'identifier' => $request->input('identifier'),
            'last_seen_at' => now(),
            'client_version' => $request->input('client.version'),
        ]);

        return new KioskResource($kiosk);
    }
}
