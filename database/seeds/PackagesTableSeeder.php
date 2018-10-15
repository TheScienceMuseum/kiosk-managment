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
        factory(\App\Package::class)
            ->create([
                'name' => 'default',
            ])
            ->each(function (\App\Package $package) {
                $package
                    ->versions()
                    ->save(
                        factory(\App\PackageVersion::class)
                            ->make([
                                'version' => '1',
                            ])
                    );
            });

    }
}
