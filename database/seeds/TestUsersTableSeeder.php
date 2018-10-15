<?php

use App\User;
use Illuminate\Database\Seeder;

class TestUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 20)->create()->each(function (User $user) {
            $user->syncRoles([
                'content writer',
                'kiosk admin',
            ]);
        });
    }
}
