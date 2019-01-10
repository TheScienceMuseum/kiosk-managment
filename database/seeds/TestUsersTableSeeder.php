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
        // Create 3 users of each role
        foreach (\Spatie\Permission\Models\Role::all() as $role) {
            $users = factory(App\User::class, 3)->create([
                'name' => $role->name,
            ]);
            foreach($users as $user) {
                $user->assignRole($role);
            }
        }

        // Create user to always be at end for testing pagination
        factory(App\User::class)->create([
            'name' => 'Test User',
        ]);
    }
}
