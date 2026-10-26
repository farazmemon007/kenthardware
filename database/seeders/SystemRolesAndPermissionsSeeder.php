<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SystemRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Definition of all system modules and their actions
        $moduleDefinitions = [
            // Core & Navigation
            'home' => ['view'],
            'profile' => ['view', 'edit'],

            // Products & Catalog
            'products' => ['view', 'create', 'edit', 'delete', 'read', 'add'],
            'categories' => ['view', 'create', 'edit', 'delete', 'read', 'add'],
            'subcategories' => ['view', 'create', 'edit', 'delete', 'read'],
            'brands' => ['view', 'create', 'edit', 'delete', 'read'],
            'units' => ['view', 'create', 'edit', 'delete', 'read'],
            'discount.products' => ['view', 'create', 'edit', 'delete'],
            'product.bookings' => ['view', 'create', 'edit', 'delete'],
            'opening_stock' => ['view', 'create', 'edit', 'delete'],

            // Purchases & Procurement
            'purchases' => ['view', 'create', 'edit', 'delete', 'read', 'add'],
            'purchase_pos' => ['view', 'create', 'edit', 'delete'],
            'purchase.returns' => ['view', 'create', 'edit', 'delete'],
            'vendors' => ['view', 'create', 'edit', 'delete', 'read', 'add'],
            'vendor.bilties' => ['view', 'create', 'edit', 'delete'],
            'inward.gatepass' => ['view', 'create', 'edit', 'delete'],

            // Sales & Customers
            'sales' => ['view', 'create', 'edit', 'delete', 'read', 'add'],
            'sales.returns' => ['view', 'create', 'edit', 'delete'],
            'customers' => ['view', 'create', 'edit', 'delete', 'read', 'add'],
            'customer_types' => ['view', 'create', 'edit', 'delete'],
            'zones' => ['view', 'create', 'edit', 'delete'],
            'sales.officers' => ['view', 'create', 'edit', 'delete'],
            'bookings' => ['view', 'create', 'edit', 'delete'],
            'price_approval' => ['view', 'request', 'respond', 'cancel'],

            // Inventory & Warehousing
            'stocks' => ['view', 'create', 'edit', 'delete'],
            'warehouse' => ['view', 'create', 'edit', 'delete'],
            'warehouse.stock' => ['view', 'create', 'edit', 'delete'],
            'stock.transfer' => ['view', 'create', 'edit', 'delete'],
            'stock.adjust' => ['view', 'create', 'edit', 'delete'],
            'storage_locations' => ['view', 'create', 'edit', 'delete'],
            'cycle_count' => ['view', 'create', 'edit', 'delete'],

            // Financial & Vouchers
            'vouchers' => ['view', 'create', 'edit', 'delete'],
            'all.vouchers' => ['view', 'create', 'edit', 'delete'],
            'chart.of.accounts' => ['view', 'create', 'edit', 'delete'],
            'expense.voucher' => ['view', 'create', 'edit', 'delete'],
            'receipts.voucher' => ['view', 'create', 'edit', 'delete'],
            'journal.voucher' => ['view', 'create', 'edit', 'delete'],
            'payment.voucher' => ['view', 'create', 'edit', 'delete'],
            'income.voucher' => ['view', 'create', 'edit', 'delete'],
            'checkbook' => ['view', 'create', 'edit', 'delete'],
            'narrations' => ['view', 'create', 'edit', 'delete'],

            // Reports
            'reporting' => ['view', 'create', 'edit', 'delete'],
            'sale.report' => ['view', 'create', 'edit', 'delete'],
            'purchase.report' => ['view', 'create', 'edit', 'delete'],
            'item.stock.report' => ['view', 'create', 'edit', 'delete'],
            'customer.ledger' => ['view', 'create', 'edit', 'delete'],
            'vendor.ledger' => ['view', 'create', 'edit', 'delete'],
            'inventory.onhand' => ['view', 'create', 'edit', 'delete'],
            'profit.loss.report' => ['view', 'create', 'edit', 'delete'],
            'recovery.report' => ['view', 'create', 'edit', 'delete'],
            'payable.report' => ['view', 'create', 'edit', 'delete'],
            'parties.balance.report' => ['view', 'create', 'edit', 'delete'],
            'aging.report' => ['view', 'create', 'edit', 'delete'],
            'balance.sheet.report' => ['view', 'create', 'edit', 'delete'],
            'executive.report' => ['view', 'create', 'edit', 'delete'],

            // Human Resource Management
            'hr.departments' => ['view', 'create', 'edit', 'delete'],
            'hr.designations' => ['view', 'create', 'edit', 'delete'],
            'hr.employees' => ['view', 'create', 'edit', 'delete'],
            'hr.shifts' => ['view', 'create', 'edit', 'delete'],
            'hr.holidays' => ['view', 'create', 'edit', 'delete'],
            'hr.attendance' => ['view', 'create', 'edit', 'delete'],
            'hr.payroll' => ['view', 'create', 'edit', 'delete'],
            'hr.leaves' => ['view', 'create', 'edit', 'delete', 'approve'],
            'hr.salary.structure' => ['view', 'create', 'edit', 'delete'],
            'hr.loans' => ['view', 'create', 'edit', 'delete', 'approve', 'schedule'],
            'hr.biometric.devices' => ['view', 'create', 'edit', 'delete'],

            // User & System Configuration
            'users' => ['view', 'create', 'edit', 'delete'],
            'roles' => ['view', 'create', 'edit', 'delete'],
            'permissions' => ['view', 'create', 'edit', 'delete'],
            'branches' => ['view', 'create', 'edit', 'delete'],
            'package.types' => ['view', 'create', 'edit', 'delete'],
            'settings' => ['view', 'create', 'edit', 'delete', 'read', 'update'],

            // Website & E-commerce Settings
            'website-settings' => ['view', 'create', 'edit', 'delete', 'update', 'upload_manage'],
            'web_products' => ['view', 'create', 'edit', 'delete', 'read'],
            'coupons' => ['view', 'create', 'edit', 'delete', 'read', 'add'],
            'web_orders' => ['view', 'create', 'edit', 'delete', 'read'],
            'web_users' => ['view', 'create', 'edit', 'delete', 'read'],
        ];

        // 2. Insert / FirstOrCreate all permissions
        foreach ($moduleDefinitions as $module => $actions) {
            foreach ($actions as $action) {
                $name = "{$module}.{$action}";
                Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            }
        }

        // Additional standalone permissions used in code
        $extraPermissions = [
            'purchase_pos.create',
            'transport.create',
        ];
        foreach ($extraPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $allPermissions = Permission::all();

        // 3. Define Standard Roles
        // Role: Super Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions($allPermissions);

        // Role: Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        // Admin gets all non-website permissions plus operational permissions
        $adminPermissions = Permission::where(function ($q) {
            $q->where('name', 'not like', 'website-settings%')
              ->where('name', 'not like', 'web_users%');
        })->get();
        $adminRole->syncPermissions($adminPermissions);

        // Role: Sales Officer (support both 'sale officer' and 'Sales Officer' naming)
        $salesOfficerPermNames = [
            // Core
            'home.view',
            'profile.view',
            'profile.edit',

            // Sales & POS
            'sales.view',
            'sales.create',
            'sales.edit',
            'sales.read',
            'sales.returns.view',
            'sales.returns.create',
            'bookings.view',
            'bookings.create',
            'bookings.edit',
            'price_approval.view',
            'price_approval.request',

            // Customers
            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.read',
            'customer_types.view',
            'zones.view',
            'sales.officers.view',

            // Products & Catalog (View-only for price/barcode lookup)
            'products.view',
            'products.read',
            'categories.view',
            'categories.read',
            'subcategories.view',
            'subcategories.read',
            'brands.view',
            'brands.read',
            'units.view',
            'units.read',
            'discount.products.view',
            'opening_stock.view',

            // Stock / Availability Check
            'stocks.view',
            'warehouse.stock.view',
            'inventory.onhand.view',
            'item.stock.report.view',

            // Cash Drawer / POS Day Opening & Closing
            'checkbook.view',
            'checkbook.create',

            // Receipts Voucher (Collecting cash against invoices)
            'receipts.voucher.view',
            'receipts.voucher.create',

            // Sales & Customer Statements
            'sale.report.view',
            'customer.ledger.view',
        ];

        $salesOfficerPerms = Permission::whereIn('name', $salesOfficerPermNames)->get();

        // Sync to existing 'sale officer' role and 'Sales Officer'
        $saleOfficerRole1 = Role::firstOrCreate(['name' => 'sale officer', 'guard_name' => 'web']);
        $saleOfficerRole1->syncPermissions($salesOfficerPerms);

        $saleOfficerRole2 = Role::firstOrCreate(['name' => 'Sales Officer', 'guard_name' => 'web']);
        $saleOfficerRole2->syncPermissions($salesOfficerPerms);

        // Role: Inventory Manager
        $inventoryManagerRole = Role::firstOrCreate(['name' => 'Inventory Manager', 'guard_name' => 'web']);
        $inventoryPerms = Permission::where(function ($q) {
            $q->where('name', 'like', 'products%')
              ->orWhere('name', 'like', 'categories%')
              ->orWhere('name', 'like', 'subcategories%')
              ->orWhere('name', 'like', 'brands%')
              ->orWhere('name', 'like', 'units%')
              ->orWhere('name', 'like', 'warehouse%')
              ->orWhere('name', 'like', 'stocks%')
              ->orWhere('name', 'like', 'stock.%')
              ->orWhere('name', 'like', 'storage_locations%')
              ->orWhere('name', 'like', 'cycle_count%')
              ->orWhere('name', 'like', 'opening_stock%')
              ->orWhere('name', 'like', 'purchases%')
              ->orWhere('name', 'like', 'purchase_pos%')
              ->orWhere('name', 'like', 'purchase.returns%')
              ->orWhere('name', 'like', 'vendors%')
              ->orWhere('name', 'like', 'inward.gatepass%')
              ->orWhere('name', 'like', 'vendor.bilties%')
              ->orWhere('name', 'like', 'item.stock.report%')
              ->orWhere('name', 'like', 'inventory.onhand%')
              ->orWhere('name', 'like', 'purchase.report%')
              ->orWhere('name', 'like', 'vendor.ledger%')
              ->orWhereIn('name', ['home.view', 'profile.view', 'profile.edit']);
        })->get();
        $inventoryManagerRole->syncPermissions($inventoryPerms);

        // Role: Accounts Manager
        $accountsManagerRole = Role::firstOrCreate(['name' => 'Accounts Manager', 'guard_name' => 'web']);
        $accountsPerms = Permission::where(function ($q) {
            $q->where('name', 'like', '%voucher%')
              ->orWhere('name', 'like', 'chart.of.accounts%')
              ->orWhere('name', 'like', 'checkbook%')
              ->orWhere('name', 'like', 'narrations%')
              ->orWhere('name', 'like', '%.ledger%')
              ->orWhere('name', 'like', '%.report%')
              ->orWhere('name', 'like', 'customers.view')
              ->orWhere('name', 'like', 'vendors.view')
              ->orWhereIn('name', ['home.view', 'profile.view', 'profile.edit']);
        })->get();
        $accountsManagerRole->syncPermissions($accountsPerms);

        // Role: HR Manager
        $hrManagerRole = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
        $hrPerms = Permission::where(function ($q) {
            $q->where('name', 'like', 'hr.%')
              ->orWhereIn('name', ['home.view', 'profile.view', 'profile.edit']);
        })->get();
        $hrManagerRole->syncPermissions($hrPerms);

        // 4. Assign Roles to Existing Users
        // Super Admin User
        $superAdminUser = User::where('email', 'superadmin@example.com')->first();
        if ($superAdminUser) {
            $superAdminUser->assignRole('Super Admin');
        }

        // Faraz Memon (Sales Employee)
        $farazUser = User::where('email', 'faraz@gmail.com')->first();
        if ($farazUser) {
            $farazUser->syncRoles(['sale officer']);
        }

        // Zeeshan Qureshi (Sales Staff)
        $zqUser = User::where('email', 'zq@gmail.com')->first();
        if ($zqUser) {
            $zqUser->syncRoles(['sale officer']);
        }

        // Reset permission cache once more
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
