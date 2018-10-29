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
            'name' => 'JP Developer',
            'email' => 'dev@joipolloi.com',
            'password' => \Illuminate\Support\Facades\Hash::make(env('DB_SEED_USERS_TABLE_PASSWORD') ?: '123qweasd'),
        ])->each(function (App\User $user) {
            $user->syncRoles(['developer']);
        });
    }
}
