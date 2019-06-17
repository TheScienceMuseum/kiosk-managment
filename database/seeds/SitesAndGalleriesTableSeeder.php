<?php

use Illuminate\Database\Seeder;

class SitesAndGalleriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites_and_galleries = [
            'The Science Museum' => [
                'Medicine Gallery',
            ],
            'The Science and Industry Museum' => [],
            'The National Railway Museum' => [],
            'The National Railway Museum (Locomotion)' => [],
            'The National Science and Media Museum' => [],
            'The Science Museum at Wroughton' => [],
        ];

        foreach ($sites_and_galleries as $site => $galleries) {

            $siteModel = factory(\App\Site::class)->create([
                'name' => $site,
            ]);

            foreach($galleries as $gallery) {
                $siteModel->galleries()->create([
                    'name' => $gallery,
                    'classes' => '',
                ]);

            }
        }
    }
}
