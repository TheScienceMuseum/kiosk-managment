<?php

use Illuminate\Database\Seeder;

class PackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultPackageJson = "{\"short_name\":\"SciMusPackage\",\"name\":\"SciMusPackage\",\"version\":123,\"icons\":[{\"src\":\"favicon.ico\",\"sizes\":\"64x64 32x32 24x24 16x16\",\"type\":\"image\/x-icon\"}],\"start_url\":\".\",\"display\":\"standalone\",\"theme_color\":\"#000000\",\"background_color\":\"#ffffff\",\"main\":\"index.html\",\"requirements\":{\"client_version\":\"0.0.1\"},\"content\":{\"titles\":{\"type\":\"text\",\"galleryName\":\"The Medicine Gallery\",\"title\":\"Perspectives on death and dying\",\"image\":null},\"contents\":[{\"articleID\":\"123-3\",\"type\":\"mixed\",\"title\":\"What is it like to donate your body to science\",\"titleImage\":\".\/media\/menu-image-4.png\",\"subpages\":[{\"pageID\":\"123-0-0\",\"type\":\"title\",\"image\":\".\/media\/menu-image-4.png\",\"title\":\"What is it like to donate your body to science?\",\"subtitle\":\"\"},{\"pageID\":\"123-0-1\",\"type\":\"textImage\",\"layout\":\"left\",\"image\":{\"imageSource\":\".\/media\/autopsy.png\",\"imageThumbnail\":\"\",\"imagePortrait\":\"\",\"imageLandscape\":\"\",\"nameText\":\"Image name\",\"sourceText\":\"Source: Science Museum\/SSPL\"},\"title\":\"Why should you donate your body to medical science?\",\"content\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"},{\"pageID\":\"123-0-2\",\"type\":\"image\",\"layout\":\"left\",\"image\":{\"imageSource\":\".\/media\/big.png\",\"imageThumbnail\":\"\",\"imagePortrait\":\"\",\"imageLandscape\":\"\",\"nameText\":\"Image name\",\"sourceText\":\"Source: Science Museum\/SSPL\"},\"title\":\"Anatomical Venuses of 18th-century Italy\",\"content\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. \"},{\"pageID\":\"123-0-3\",\"type\":\"textImage\",\"layout\":\"right\",\"image\":{\"imageSource\":\".\/media\/autopsy.png\",\"imageThumbnail\":\"\",\"imagePortrait\":\"\",\"imageLandscape\":\"\",\"nameText\":\"Image name\",\"sourceText\":\"Source: Science Museum\/SSPL\"},\"title\":\"Why should you donate your body to medical science?\",\"content\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"}]},{\"articleID\":\"123-0\",\"type\":\"video\",\"title\":\"Digital autopsy: the future of post-mortem?\",\"titleImage\":\".\/media\/menu-image-1.png\",\"videoSrc\":\".\/videos\/27c5c9b73b18bc62e39f0ef6533d6caf.mp4\",\"image\":{\"imageSource\":\"\",\"imageThumbnail\":\"\",\"imagePortrait\":\"\",\"imageLandscape\":\"\",\"nameText\":\"\",\"sourceText\":\"\"}},{\"articleID\":\"123-1\",\"type\":\"mixed\",\"title\":\"What it is like to perform a post-mortem?\",\"titleImage\":\".\/media\/menu-image-2.png\",\"subpages\":[{\"pageID\":\"123-0-0\",\"type\":\"title\",\"image\":\".\/media\/menu-image-2.png\",\"title\":\"What it is like to perform a post-mortem\",\"subtitle\":\"\"}]},{\"articleID\":\"123-2\",\"type\":\"video\",\"title\":\"What is it like to be a student doing your first dissection\",\"titleImage\":\".\/media\/menu-image-3.png\",\"videoSrc\":\".\/videos\/0fafe4959802c00a27cacd515099f40c.mp4 \",\"image\":{\"imageSource\":\"\",\"imageThumbnail\":\"\",\"imagePortrait\":\"\",\"imageLandscape\":\"\",\"nameText\":\"\",\"sourceText\":\"\"}}]}}";

        factory(App\Package::class, 1)
            ->create([
                'name' => 'default',
            ])
            ->each(function (\App\Package $package) use ($defaultPackageJson) {
                $package
                    ->versions()
                    ->saveMany([
                        factory(\App\PackageVersion::class)
                            ->make([
                                'version' => 1,
                                'status' => 'approved',
                                'data' => json_decode($defaultPackageJson, true),
                            ]),
                        factory(\App\PackageVersion::class)
                            ->make([
                                'version' => 2,
                                'status' => 'pending',
                                'data' => json_decode($defaultPackageJson, true),
                            ]),
                        factory(\App\PackageVersion::class)
                            ->make([
                                'version' => 3,
                                'status' => 'draft',
                                'data' => json_decode($defaultPackageJson, true),
                            ]),
                    ]);
            });
    }
}
