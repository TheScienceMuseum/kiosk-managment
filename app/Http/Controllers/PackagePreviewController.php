<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackagePreviewBuildRequest;
use App\Http\Requests\PackagePreviewShowRequest;
use App\Jobs\BuildPreviewPackageFromVersion;
use App\PackageVersion;
use App\PackageVersionPreview;

class PackagePreviewController extends Controller
{
    public function build(PackagePreviewBuildRequest $request, PackageVersion $packageVersion)
    {
        /** @var PackageVersionPreview $preview */
        $preview = $packageVersion->previews()->create();

        $this->dispatch(new BuildPreviewPackageFromVersion($preview));

        return redirect(route('preview.show', [$packageVersion, $preview]));
    }

    public function show(PackagePreviewShowRequest $request, PackageVersion $packageVersion, PackageVersionPreview $packageVersionPreview)
    {
        if ($packageVersionPreview->build_complete) {
            return redirect('/storage/previews/'.$packageVersionPreview->preview_path.'/index.html');
        }

        return view('preview-building')->with('preview', $packageVersionPreview);
    }
}
