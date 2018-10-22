<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PackageResource;
use App\Package;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PackageController extends Controller
{
    public function index()
    {
        $kiosks = QueryBuilder::for(Package::class)
            ->allowedFilters([
                'name',
            ])
            ->jsonPaginate()
        ;

        return PackageResource::collection($kiosks);
    }
    public function store(Request $request)
    {
        //
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
