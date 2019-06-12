<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PackageDestroyRequest;
use App\Http\Requests\PackageDuplicateRequest;
use App\Http\Requests\PackageIndexRequest;
use App\Http\Requests\PackageShowRequest;
use App\Http\Requests\PackageStoreRequest;
use App\Http\Requests\PackageUpdateRequest;
use App\Http\Resources\PackageResource;
use App\Package;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class PackageController
 * @package App\Http\Controllers\Api
 */
class PackageController extends Controller
{
    /**
     * @param PackageIndexRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(PackageIndexRequest $request)
    {
        $kiosks = QueryBuilder::for(Package::class)
            ->allowedFilters([
                'name',
            ])
            ->jsonPaginate()
        ;

        return PackageResource::collection($kiosks);
    }

    /**
     * @param PackageStoreRequest $request
     * @return PackageResource
     */
    public function store(PackageStoreRequest $request)
    {
        $package = Package::create([
            'name' => $request->input('name'),
        ]);

        $package->createVersion();

        return new PackageResource($package);
    }

    /**
     * @param PackageShowRequest $request
     * @param Package $package
     * @return PackageResource
     */
    public function show(PackageShowRequest $request, Package $package) : PackageResource
    {
        return new PackageResource($package);
    }

    /**
     * @param PackageUpdateRequest $request
     * @param Package $package
     * @return PackageResource
     */
    public function update(PackageUpdateRequest $request, Package $package) : PackageResource
    {
        $package->update([]);

        return new PackageResource($package);
    }

    /**
     * @param PackageDuplicateRequest $request
     * @param Package $package
     * @return PackageResource
     */
    public function duplicate(PackageDuplicateRequest $request, Package $package) : PackageResource
    {
        $newPackage = Package::create([
            'name' => $request->input('name')
        ]);

        $package->versions->last()->createNewVersion($newPackage);

        return new PackageResource($newPackage);
    }

    /**
     * @param PackageDestroyRequest $request
     * @param Package $package
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(PackageDestroyRequest $request, Package $package)
    {
        $package->delete();

        return response('', 204);
    }
}
