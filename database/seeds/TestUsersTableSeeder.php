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
        factory(App\User::class)->create([
            'name' => 'Lawrence',
            'email' => 'lawrence@joipolloi.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123qweasd'),
        ])->each(function (User $user) {
            $user->syncRoles([
                'developer',
                'content writer',
                'kiosk admin',
            ]);
        });

        factory(App\User::class, 20)->create()->each(function (User $user) {
            $user->syncRoles([
                'content writer',
                'kiosk admin',
            ]);
        });
    }
}
