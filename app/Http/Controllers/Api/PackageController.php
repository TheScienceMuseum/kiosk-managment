<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PackageIndexRequest;
use App\Http\Requests\PackageStoreRequest;
use App\Http\Resources\PackageResource;
use App\Package;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PackageController extends Controller
{
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

    public function store(PackageStoreRequest $request)
    {
        $package = Package::create([
            'name' => $request->input('name'),
        ]);

        return new PackageResource($package);
    }

    public function show(Package $package)
    {
        //
    }

    public function update(Request $request, Package $package)
    {
        //
    }

    public function destroy(Package $package)
    {
        //
    }
}
