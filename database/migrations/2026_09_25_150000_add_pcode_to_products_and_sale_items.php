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
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'p_code')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('p_code', 50)->nullable()->after('barcode_path');
            });
        }

        if (Schema::hasTable('sale_items') && !Schema::hasColumn('sale_items', 'p_code')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->string('p_code', 50)->nullable()->after('purchase_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'p_code')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('p_code');
            });
        }

        if (Schema::hasTable('sale_items') && Schema::hasColumn('sale_items', 'p_code')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->dropColumn('p_code');
            });
        }
    }
};
