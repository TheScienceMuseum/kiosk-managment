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
        // Create one user of each role
        foreach (\Spatie\Permission\Models\Role::all() as $role) {
            $user = factory(App\User::class)->create([
                'name' => $role->name,
            ]);

            $user->assignRole($role);
        }
    }
}
