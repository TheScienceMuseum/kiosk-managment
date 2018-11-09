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
        factory(App\Package::class, 1)
            ->create([
                'name' => 'default',
            ])
            ->each(function (\App\Package $package) {
                $package
                    ->versions()
                    ->saveMany([
                        factory(\App\PackageVersion::class)
                            ->make([
                                'version' => 1,
                                'status' => 'approved',
                            ]),
                        factory(\App\PackageVersion::class)
                            ->make([
                                'version' => 2,
                                'status' => 'approved',
                            ]),
                    ]);
            });
    }
}
