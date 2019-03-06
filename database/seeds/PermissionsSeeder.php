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
        // Backend Permissions
        $perm_view_queue_dashboard = Permission::create(['name' => 'view queue dashboard']);

        $perm_group_backend = [$perm_view_queue_dashboard];

        // User Permissions
        $perm_create_new_users = Permission::create(['name' => 'create new users']);
        $perm_view_all_users = Permission::create(['name' => 'view all users']);
        $perm_edit_all_users = Permission::create(['name' => 'edit all users']);
        $perm_destroy_all_users = Permission::create(['name' => 'destroy all users']);
        $perm_view_all_user_roles = Permission::create(['name' => 'view all user roles']);

        $perm_group_user = [
            $perm_create_new_users,
            $perm_view_all_users,
            $perm_edit_all_users,
            $perm_destroy_all_users,
            $perm_view_all_user_roles,
        ];

        // Package Permissions
        $perm_create_package = Permission::create(['name' => 'create new packages']);
        $perm_view_package = Permission::create(['name' => 'view all packages']);
        $perm_edit_package = Permission::create(['name' => 'edit all packages']);
        $perm_delete_package = Permission::create(['name' => 'delete all packages']);
        $perm_publish_package = Permission::create(['name' => 'publish all packages']);
        $perm_test_package = Permission::create(['name' => 'test all packages']);

        $perm_group_package = [
            $perm_create_package,
            $perm_view_package,
            $perm_edit_package,
            $perm_publish_package,
            $perm_test_package,
            $perm_delete_package,
        ];

        // Kiosk Permissions
        $perm_create_new_kiosks = Permission::create(['name' => 'create new kiosks']);
        $perm_view_all_kiosks = Permission::create(['name' => 'view all kiosks']);
        $perm_edit_all_kiosks = Permission::create(['name' => 'edit all kiosks']);
        $perm_destroy_all_kiosks = Permission::create(['name' => 'destroy all kiosks']);
        $perm_deploy_packages_to_all_kiosks = Permission::create(['name' => 'deploy packages to all kiosks']);
        $perm_view_kiosk_logs = Permission::create(['name' => 'view kiosk logs']);

        $perm_group_kiosk = [
            $perm_create_new_kiosks,
            $perm_view_all_kiosks,
            $perm_edit_all_kiosks,
            $perm_destroy_all_kiosks,
            $perm_deploy_packages_to_all_kiosks,
            $perm_view_kiosk_logs,
        ];

        // Help Topic Permissions
        $perm_edit_all_help_topics = Permission::create(['name' => 'edit all help topics']);

        $perm_group_help_topics = [
            $perm_edit_all_help_topics,
        ];

        // System Developers get all permissions
        Role::create(['name' => 'developer'])
            ->syncPermissions(Permission::all());

        // Admin
        // General super user
        Role::create(['name' => 'admin'])
            ->syncPermissions(array_merge(
                $perm_group_user,
                $perm_group_package,
                $perm_group_kiosk,
                $perm_group_help_topics
            ))
        ;

        // Tech Admin
        // Overseeing the status of the kiosks,
        // whether they are online and if they
        // have correctly received packages.
        // No access to editorial material.
        Role::create(['name' => 'tech admin'])
            ->syncPermissions(array_merge(
                $perm_group_kiosk,
                [$perm_view_package]
            ))
        ;

        // Content Author
        // For creating/editing content in the system,
        // but not allow to push to kiosks.
        // No access to kiosk status.
        Role::create(['name' => 'content author'])
            ->syncPermissions(array_merge(
                [
                    $perm_create_package,
                    $perm_view_package,
                    $perm_edit_package,
                    $perm_test_package,
                ]
            ))
        ;

        // Content Editor
        // Able to sign off content,
        // and push to kiosks.
        // No access to kiosk status.
        Role::create(['name' => 'content editor'])
            ->syncPermissions(array_merge(
                $perm_group_package,
                [
                    $perm_deploy_packages_to_all_kiosks
                ]
            ))
        ;
    }
}
