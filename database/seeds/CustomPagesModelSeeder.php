<?php

use Illuminate\Database\Seeder;

class CustomPagesModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customPage = \App\CustomPage::create([
            'name' => '3D Model - IronMan',
            'data' => [],
        ]);

        $customPageData = json_decode("{\"type\":\"model\",\"title\":\"Iron Man\",\"titleImage\":{\"assetType\":\"image\",\"assetId\":8,\"assetMime\":\"image/jpeg\"},\"asset\":{\"assetId\":3,\"obj\":\"IronMan.obj\",\"mtl\":\"IronMan.mtl\",\"assetType\":\"model\",\"nameText\":\"Iron Pan\",\"sourceText\":\"Free3D - https://free3d.com/3d-model/ironman-rigged-original-model--98611.html\",\"position\":[0,-120,0],\"rotation\":[0,0,0],\"background\":\"#eee\"},\"subpages\":[{\"type\":\"title\",\"title\":\"Title Page\",\"content\":[\"Has he lost his mind?\",\"Can he see or is he blind?\",\"Can he walk at all,\",\"Or if he moves will he fall?\",\"Is he alive or dead?\",\"Has he thoughts within his head?\",\"We'll just pass him there\",\"Why should we even care?\"],\"camera\":{\"position\":[0,250,350],\"rotation\":[0,20,200]}},{\"type\":\"hotspot\",\"title\":\"Hotspot Tag 1\",\"content\":[\"He was turned to steel\",\"In the great magnetic field\",\"Where he traveled time\",\"For the future of mankind\",\"<img width='100%' src='https://cdn.shopify.com/s/files/1/1176/5302/products/7702-Iron-Man-Marvel-Happy-Feet-Slippers-08_2000x.jpg?v=1537191119'>\"],\"camera\":{\"position\":[-80,-275,-145],\"rotation\":[0,20,200]},\"hotspot\":{\"position\":[12,-80,-15]}},{\"type\":\"hotspot\",\"title\":\"Hotspot Tag 2\",\"content\":[\"Nobody wants him\",\"He just stares at the world\",\"Planning his vengeance\",\"That he will soon unfold\",\"<img width='100%' src='https://i0.wp.com/lylesmoviefiles.com/wp-content/uploads/2017/07/Hot-Toys-Captain-America-Civil-War-Iron-Man-figure-review-hands-on-hips.jpg?ssl=1'>\"],\"camera\":{\"position\":[90,120,70],\"rotation\":[0,20,200]},\"hotspot\":{\"position\":[30,30,3]}}]}");

        $model = $customPage->copyMedia('./database/seeds/assets/ironman.zip')->toMediaCollection('assets');
        $customPageData->asset->assetId = $model->id;

        $titleImage = $customPage->copyMedia('./database/seeds/assets/iron-man.jpg')->toMediaCollection('images');
        $customPageData->titleImage->assetId = $titleImage->id;

        $customPage->data = $customPageData;
        $customPage->save();
    }
}
