<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
        ])->each(function (App\User $user) {
            $user->syncRoles([
                'developer',
                'administrator',
                'content writer',
                'kiosk admin',
            ]);
        });
    }
}
