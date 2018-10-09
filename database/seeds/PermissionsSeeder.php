<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_developer = Role::create(['name' => 'developer']);
        $role_developer->givePermissionTo(Permission::create(['name' => 'view all logs']));

        $role_content_writer = Role::create(['name' => 'content writer']);
        $role_content_writer->givePermissionTo(Permission::create(['name' => 'edit content package']));
        $role_content_writer->givePermissionTo(Permission::create(['name' => 'build content package']));
        $role_content_writer->givePermissionTo(Permission::create(['name' => 'test content package']));

        $role_kiosk_admin = Role::create(['name' => 'kiosk admin']);
        $role_kiosk_admin->givePermissionTo(Permission::create(['name' => 'view all kiosks']));
        $role_kiosk_admin->givePermissionTo(Permission::create(['name' => 'deploy packages to all kiosks']));
    }
}
