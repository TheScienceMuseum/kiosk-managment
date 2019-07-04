<?php

namespace App\Http\Controllers\Api;

use App\Events\PackageVersionSubmittedForApproval;
use App\Http\Requests\PackageVersionDeployRequest;
use App\Http\Requests\PackageVersionDestroyRequest;
use App\Http\Requests\PackageVersionIndexRequest;
use App\Http\Requests\PackageVersionSearchAssetRequest;
use App\Http\Requests\PackageVersionShowRequest;
use App\Http\Requests\PackageVersionUpdateRequest;
use App\Http\Requests\PackageVersionUploadAssetRequest;
use App\Http\Resources\PackageVersionAssetResource;
use App\Http\Resources\PackageVersionResource;
use App\Kiosk;
use App\Package;
use App\PackageVersion;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\MediaLibrary\Models\Media;
use Spatie\QueryBuilder\QueryBuilder;

class PackageVersionController extends Controller
{
    /**
     * @param PackageVersionIndexRequest $request
     * @param Package $package
     * @return mixed
     */
    public function index(PackageVersionIndexRequest $request, Package $package = null): ResourceCollection
    {
        $kiosks = QueryBuilder::for(PackageVersion::class)
            ->orderByDesc('version')
            ->allowedFilters([
                'progress',
                'status',
                'version',
            ]);

        if ($package) {
            $kiosks = $kiosks->where('package_id', $package->id);
        }

        return PackageVersionResource::collection($kiosks->jsonPaginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Package $package
     * @return PackageVersionResource
     */
    public function store(Request $request, Package $package): PackageVersionResource
    {
        $packageVersion = $package->createVersion();

        return new PackageVersionResource($packageVersion);
    }

    /**
     * Display the specified resource.
     *
     * @param PackageVersionShowRequest $request
     * @param Package $package
     * @param  \App\PackageVersion $packageVersion
     * @return PackageVersionResource
     */
    public function show(PackageVersionShowRequest $request, Package $package, PackageVersion $packageVersion): PackageVersionResource
    {
        return new PackageVersionResource($packageVersion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PackageVersionUpdateRequest $request
     * @param Package $package
     * @param  \App\PackageVersion $packageVersion
     * @return PackageVersionResource
     */
    public function update(PackageVersionUpdateRequest $request, Package $package, PackageVersion $packageVersion): PackageVersionResource
    {
        $currentVersion = (object)$packageVersion->toArray();

        if ($request->input('status') === 'approved' && $currentVersion->status !== 'approved') {
            if ($request->user()->cannot('approve', $packageVersion)) {
                abort(403, 'You do not have permission to approve packages');
            }
        }

        if ($request->has('status')) {
            $packageVersion->update([
                'status' => $request->input('status'),
            ]);
        }

        if ($request->has('package_data')) {
            $packageVersion->update([
                'data' => $request->input('package_data'),
            ]);
        }

        if ($request->input('status') === 'pending' && in_array($currentVersion->status, ['draft', 'failed'])) {
            // The package has been submitted for approval, triggering event
            event(new PackageVersionSubmittedForApproval($packageVersion, User::find($request->input('approval'))));
        }

        if (array_keys($request->all()) === ['package_data'] && $currentVersion->status === 'failed') {
            $packageVersion->update([
                'status' => 'draft',
            ]);
        }

        return new PackageVersionResource($packageVersion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PackageVersionDeployRequest $request
     * @param Package $package
     * @param PackageVersion $packageVersion
     * @return PackageVersionResource
     */
    public function deploy(PackageVersionDeployRequest $request, Package $package, PackageVersion $packageVersion): PackageVersionResource
    {
        $kiosk = Kiosk::findOrFail($request->input('kiosk'));

        $kiosk->assigned_package_version()->associate($packageVersion);
        $kiosk->save();

        return new PackageVersionResource($packageVersion);
    }

    /**
     * @param PackageVersionDestroyRequest $request
     * @param Package $package
     * @param PackageVersion $packageVersion
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(PackageVersionDestroyRequest $request, Package $package, PackageVersion $packageVersion)
    {
        $packageVersion->forceDelete();

        return response('', 204);
    }

    /**
     * @param PackageVersionUploadAssetRequest $request
     * @param Package $package
     * @param PackageVersion $packageVersion
     * @return PackageVersionResource
     */
    public function uploadAsset(PackageVersionUploadAssetRequest $request, Package $package, PackageVersion $packageVersion)
    {
        $media = $packageVersion->addMediaFromRequest('file')->toMediaCollection();

        if (strpos($media->file_name, '.vtt') !== false) {
            $media->mime_type = 'text/vtt';
            $media->save();
        }

        return new PackageVersionResource($packageVersion);
    }

    /**
     * @param PackageVersionSearchAssetRequest $request
     * @param Package $package
     * @param PackageVersion $packageVersion
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function searchAsset(PackageVersionSearchAssetRequest $request, Package $package, PackageVersion $packageVersion)
    {
        $medias = QueryBuilder::for(Media::class)
            ->where('model_type', 'App\PackageVersion')
            ->where('model_id', $packageVersion->id)
            ->orderByDesc('created_at')
            ->allowedFilters([
                'file_name',
                'mime_type',
            ])
            ->get();

        return PackageVersionAssetResource::collection($medias);
    }

    /**
     * @param PackageVersionUploadAssetRequest $request
     * @param Package $package
     * @param PackageVersion $packageVersion
     * @param Media $media
     * @return PackageVersionAssetResource
     */
    public function showAsset(PackageVersionUploadAssetRequest $request, Package $package, PackageVersion $packageVersion, Media $media)
    {
        return new PackageVersionAssetResource($media);
    }
}
