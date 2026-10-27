<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run other seeders
        $this->call([
            CategorySeeder::class,
            WarehouseSeeder::class,
            StorageLocationsListSeeder::class,
            ModulesTableSeeder::class, 
            RoleSeeder::class, // Complete Spatie RBAC & All 7 Roles
            SuperadminSeeder::class, 
        ]);
    }
}
