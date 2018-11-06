<?php

use Illuminate\Database\Seeder;

class TestKiosksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a registered, but not setup kiosk
        $kiosk = factory(App\Kiosk::class)->make([
            'name' => 'test-kiosk-registered',
            'location' => 'under the stairs',
            'asset_tag' => 'tt-kk-01',
            'identifier' => 'test-kiosk-registered',
        ]);

        $kiosk->assigned_package_version()->associate(\App\Package::whereName('default')->first()->versions()->first());
        $kiosk->save();

        // Create a few kiosks that are fully set up
        factory(App\Kiosk::class, 4)->create();
    }
}
