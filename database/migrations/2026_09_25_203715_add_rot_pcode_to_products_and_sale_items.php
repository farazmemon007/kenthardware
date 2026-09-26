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
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'rot_p_code')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('rot_p_code', 50)->nullable()->after('p_code');
            });
        }

        if (Schema::hasTable('sale_items') && !Schema::hasColumn('sale_items', 'rot_p_code')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->string('rot_p_code', 50)->nullable()->after('p_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'rot_p_code')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('rot_p_code');
            });
        }

        if (Schema::hasTable('sale_items') && Schema::hasColumn('sale_items', 'rot_p_code')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->dropColumn('rot_p_code');
            });
        }
    }
};
