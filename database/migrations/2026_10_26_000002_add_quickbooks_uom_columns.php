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
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'uom_data')) {
            Schema::table('products', function (Blueprint $table) {
                $table->longText('uom_data')->nullable()->after('color');
            });
        }

        if (Schema::hasTable('sale_items')) {
            Schema::table('sale_items', function (Blueprint $table) {
                if (!Schema::hasColumn('sale_items', 'uom_name')) {
                    $table->string('uom_name', 100)->nullable()->after('unit_id');
                }
                if (!Schema::hasColumn('sale_items', 'uom_ratio')) {
                    $table->decimal('uom_ratio', 14, 4)->default(1)->after('uom_name');
                }
            });
        }

        if (Schema::hasTable('sale_return_items')) {
            Schema::table('sale_return_items', function (Blueprint $table) {
                if (!Schema::hasColumn('sale_return_items', 'uom_name')) {
                    $table->string('uom_name', 100)->nullable()->after('unit');
                }
                if (!Schema::hasColumn('sale_return_items', 'uom_ratio')) {
                    $table->decimal('uom_ratio', 14, 4)->default(1)->after('uom_name');
                }
            });
        }

        if (Schema::hasTable('purchase_items')) {
            Schema::table('purchase_items', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_items', 'uom_name')) {
                    $table->string('uom_name', 100)->nullable()->after('unit');
                }
                if (!Schema::hasColumn('purchase_items', 'uom_ratio')) {
                    $table->decimal('uom_ratio', 14, 4)->default(1)->after('uom_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'uom_data')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('uom_data');
            });
        }

        if (Schema::hasTable('sale_items')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('sale_items', 'uom_name')) $cols[] = 'uom_name';
                if (Schema::hasColumn('sale_items', 'uom_ratio')) $cols[] = 'uom_ratio';
                if (!empty($cols)) $table->dropColumn($cols);
            });
        }

        if (Schema::hasTable('sale_return_items')) {
            Schema::table('sale_return_items', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('sale_return_items', 'uom_name')) $cols[] = 'uom_name';
                if (Schema::hasColumn('sale_return_items', 'uom_ratio')) $cols[] = 'uom_ratio';
                if (!empty($cols)) $table->dropColumn($cols);
            });
        }

        if (Schema::hasTable('purchase_items')) {
            Schema::table('purchase_items', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('purchase_items', 'uom_name')) $cols[] = 'uom_name';
                if (Schema::hasColumn('purchase_items', 'uom_ratio')) $cols[] = 'uom_ratio';
                if (!empty($cols)) $table->dropColumn($cols);
            });
        }
    }
};
