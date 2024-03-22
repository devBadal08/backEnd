<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //manager permition for admin
        $manager_list = Permission::create(['name' => 'manager.list']);
        $manager_view = Permission::create(['name' => 'manager.view']);
        $manager_create = Permission::create(['name' => 'manager.create']);
        $manager_update = Permission::create(['name' => 'manager.update']);
        $manager_delete = Permission::create(['name' => 'manager.delete']);

        //user Permitions for manager
        $user_list = Permission::create(['name' => 'user.list']);
        $user_view = Permission::create(['name' => 'user.view']);
        $user_create = Permission::create(['name' => 'user.create']);
        $user_update = Permission::create(['name' => 'user.update']);
        $user_delete = Permission::create(['name' => 'user.delete']);

        $profile_list = Permission::create(['name' => 'profile.list']);
        $profile_view = Permission::create(['name' => 'profile.view']);
        $profile_create = Permission::create(['name' => 'profile.create']);
        $profile_update = Permission::create(['name' => 'profile.update']);
        $profile_delete = Permission::create(['name' => 'profile.delete']);

        //profile permition for user
        $profile_list = Permission::create(['name' => 'profile.list']);


        //admin
        $admin = User::create([
            'first_name' => 'Admin',
            // 'last_name' => 'use',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password')
        ]);

        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->givePermissionTo([
            $manager_create,
            $manager_list,
            $manager_update,
            $manager_view,
            $manager_delete,
            $profile_list,
            // $profile_view,
            // $profile_create,
            // $profile_update,
            // $profile_delete,

        ]);

        $admin->assignRole('admin');
        $admin->givePermissionTo([
            $manager_create,
            $manager_list,
            $manager_update,
            $manager_view,
            $manager_delete,
            $profile_list,
        ]);

        //Manager
        $manager = User::create([
            'first_name' => 'manager',
            // 'last_name' => 'use',
            'email' => 'manager@manager.com',
            'password' => bcrypt('password')
        ]);

        $manager_role = Role::create(['name' => 'manager']);
        $manager_role->givePermissionTo([
            $user_create,
            $user_list,
            $user_update,
            $user_view,
            $user_delete,
            $profile_list,
            $profile_view,
            $profile_create,
            $profile_update,
            $profile_delete,

        ]);

        $manager->assignRole('manager');
        $manager->givePermissionTo([
            $user_create,
            $user_list,
            $user_update,
            $user_view,
            $user_delete,
            $profile_list,
            $profile_view,
            $profile_create,
            $profile_update,
            $profile_delete,

        ]);

        // User
        $user = User::create([
            'first_name' => 'user',
            // 'last_name' => 'use',
            'email' => 'user@user.com',
            'password' => bcrypt('password')
        ]);

        $user_role = Role::create(['name' => 'user']);

        $user->assignRole('user');
        $user->givePermissionTo([
            $profile_list,

        ]);

        $user_role->givePermissionTo([
            $profile_list,

        ]);
    }
}
