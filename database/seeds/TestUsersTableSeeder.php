<?php

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
        ]);

        factory(App\User::class, 20)->create();
    }
}
