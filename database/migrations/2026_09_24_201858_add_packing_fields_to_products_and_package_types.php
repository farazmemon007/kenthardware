<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'packing_type')) {
                    $table->string('packing_type')->nullable()->default('standard')->after('pieces_per_box');
                }
                if (!Schema::hasColumn('products', 'packing_name')) {
                    $table->string('packing_name')->nullable()->after('packing_type');
                }
            });
        }

        if (Schema::hasTable('package_types')) {
            Schema::table('package_types', function (Blueprint $table) {
                if (!Schema::hasColumn('package_types', 'pieces_per_box')) {
                    $table->integer('pieces_per_box')->default(1)->after('name');
                }
            });

            // Seed common standard packings if table is empty
            if (DB::table('package_types')->count() === 0) {
                DB::table('package_types')->insert([
                    ['name' => 'Single / Loose (1 Pc)', 'pieces_per_box' => 1, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Pack of 6 (6 Pcs)', 'pieces_per_box' => 6, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Pack of 10 (10 Pcs)', 'pieces_per_box' => 10, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Dozen (12 Pcs)', 'pieces_per_box' => 12, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Box of 20 (20 Pcs)', 'pieces_per_box' => 20, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Box of 24 (24 Pcs)', 'pieces_per_box' => 24, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Box of 50 (50 Pcs)', 'pieces_per_box' => 50, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Carton of 100 (100 Pcs)', 'pieces_per_box' => 100, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Carton of 200 (200 Pcs)', 'pieces_per_box' => 200, 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        }

        // Seed common standard units if table is empty
        if (Schema::hasTable('units') && DB::table('units')->count() === 0) {
            DB::table('units')->insert([
                ['name' => 'Pcs', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Carton', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Box', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Dozen', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Kg', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Gm', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Meter', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Ft', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Roll', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Set', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'packing_name')) {
                    $table->dropColumn('packing_name');
                }
                if (Schema::hasColumn('products', 'packing_type')) {
                    $table->dropColumn('packing_type');
                }
            });
        }

        if (Schema::hasTable('package_types')) {
            Schema::table('package_types', function (Blueprint $table) {
                if (Schema::hasColumn('package_types', 'pieces_per_box')) {
                    $table->dropColumn('pieces_per_box');
                }
            });
        }
    }
};
