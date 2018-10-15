<?php

use Illuminate\Database\Seeder;

class TestPackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Package::class, 4)
            ->create()
            ->each(function (\App\Package $package) {
                $package
                    ->versions()
                    ->save(
                        factory(\App\PackageVersion::class)
                            ->make()
                    );
            });
    }
}
