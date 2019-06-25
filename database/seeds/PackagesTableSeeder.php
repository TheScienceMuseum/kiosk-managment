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
        $defaultPackageData = [
            'main' => 'index.html',
            'requirements' => [
                'client_version' => '0.0.1',
            ],
            'gallery' => 1,
            'content' => [
                'titles' => [
                    'type' => 'text',
                    'image' => NULL,
                    'title' => 'Main title displayed after attractor is clicked',
                    'galleryName' => 'The Gallery this Kiosk is in',
                    'attractorImage' => NULL,
                ],
                'contents' => [

                ],
            ],
        ];

        factory(App\Package::class, 1)
            ->create([
                'name' => 'default',
                'aspect_ratio' => '16:9',
            ])
            ->each(function (\App\Package $package) use ($defaultPackageData) {
                $package
                    ->versions()
                    ->saveMany([
                        factory(\App\PackageVersion::class)
                            ->make([
                                'version' => 1,
                                'status' => 'approved',
                                'data' => $defaultPackageData,
                            ]),
                        factory(\App\PackageVersion::class)
                            ->make([
                                'version' => 2,
                                'status' => 'pending',
                                'data' => $defaultPackageData,
                            ]),
                        factory(\App\PackageVersion::class)
                            ->make([
                                'version' => 3,
                                'status' => 'draft',
                                'data' => $defaultPackageData,
                            ]),
                    ]);
            });
    }
}
