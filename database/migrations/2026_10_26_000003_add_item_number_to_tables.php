<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'item_number')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('item_number', 100)->nullable()->index()->after('item_code');
            });
        }

        if (Schema::hasTable('sale_items') && !Schema::hasColumn('sale_items', 'item_number')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->string('item_number', 100)->nullable()->after('product_id');
            });
        }

        if (Schema::hasTable('purchase_items') && !Schema::hasColumn('purchase_items', 'item_number')) {
            Schema::table('purchase_items', function (Blueprint $table) {
                $table->string('item_number', 100)->nullable()->after('product_id');
            });
        }

        if (Schema::hasTable('sale_return_items') && !Schema::hasColumn('sale_return_items', 'item_number')) {
            Schema::table('sale_return_items', function (Blueprint $table) {
                $table->string('item_number', 100)->nullable()->after('product_id');
            });
        }

        if (Schema::hasTable('purchase_return_items') && !Schema::hasColumn('purchase_return_items', 'item_number')) {
            Schema::table('purchase_return_items', function (Blueprint $table) {
                $table->string('item_number', 100)->nullable()->after('product_id');
            });
        }

        if (Schema::hasTable('cycle_count_items') && !Schema::hasColumn('cycle_count_items', 'item_number')) {
            Schema::table('cycle_count_items', function (Blueprint $table) {
                $table->string('item_number', 100)->nullable()->after('item_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'item_number')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('item_number');
            });
        }

        if (Schema::hasTable('sale_items') && Schema::hasColumn('sale_items', 'item_number')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->dropColumn('item_number');
            });
        }

        if (Schema::hasTable('purchase_items') && Schema::hasColumn('purchase_items', 'item_number')) {
            Schema::table('purchase_items', function (Blueprint $table) {
                $table->dropColumn('item_number');
            });
        }

        if (Schema::hasTable('sale_return_items') && Schema::hasColumn('sale_return_items', 'item_number')) {
            Schema::table('sale_return_items', function (Blueprint $table) {
                $table->dropColumn('item_number');
            });
        }

        if (Schema::hasTable('purchase_return_items') && Schema::hasColumn('purchase_return_items', 'item_number')) {
            Schema::table('purchase_return_items', function (Blueprint $table) {
                $table->dropColumn('item_number');
            });
        }

        if (Schema::hasTable('cycle_count_items') && Schema::hasColumn('cycle_count_items', 'item_number')) {
            Schema::table('cycle_count_items', function (Blueprint $table) {
                $table->dropColumn('item_number');
            });
        }
    }
};
