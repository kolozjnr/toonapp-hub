<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $managePlugins = Permission::firstOrCreate(['name' => 'manage plugins', 'guard_name' => 'web']);
        $manageOrders = Permission::firstOrCreate(['name' => 'manage orders', 'guard_name' => 'web']);
        $manageCustomers = Permission::firstOrCreate(['name' => 'manage customers', 'guard_name' => 'web']);
        $manageSettings = Permission::firstOrCreate(['name' => 'manage settings', 'guard_name' => 'web']);

        $adminRole->givePermissionTo([
            $managePlugins, $manageOrders, $manageCustomers, $manageSettings,
        ]);
        $superadminRole->givePermissionTo([
            $managePlugins, $manageOrders, $manageCustomers, $manageSettings,
        ]);

        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@toonapphub.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('superadmin@123')]
        );
        $superadmin->assignRole('superadmin');

        $admin = User::firstOrCreate(
            ['email' => 'admin@toonapphub.com'],
            ['name' => 'Store Admin', 'password' => bcrypt('admin@123')]
        );
        $admin->assignRole('admin');

        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );
        $user->assignRole('user');
    }
}
