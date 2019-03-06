<?php

use Illuminate\Database\Seeder;

class HelpTopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contexts = [
            ['context' => '/dashboard', 'content' => '#The Dashboard'],
            ['context' => '/admin/users', 'content' => '#User Management'],
            ['context' => '/admin/users/create', 'content' => '#User Creation'],
            ['context' => '/admin/users/#', 'content' => '#User Edit'],
            ['context' => '/admin/kiosks', 'content' => '#Kiosk Management'],
            ['context' => '/admin/kiosks/#', 'content' => '#Kiosk View'],
            ['context' => '/admin/packages', 'content' => '#Package Management'],
            ['context' => '/admin/packages/#', 'content' => '#Package View'],
            ['context' => '/editor/#/version/#', 'content' => '#Package Editor'],
        ];

        foreach ($contexts as $context) {
            factory(\App\HelpTopic::class)->create($context);
        }
    }
}
