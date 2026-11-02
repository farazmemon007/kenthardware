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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'can_be_sold')) {
                $table->boolean('can_be_sold')->default(1)->after('is_active');
            }
            if (!Schema::hasColumn('products', 'can_be_purchased')) {
                $table->boolean('can_be_purchased')->default(1)->after('can_be_sold');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'can_be_purchased')) {
                $table->dropColumn('can_be_purchased');
            }
            if (Schema::hasColumn('products', 'can_be_sold')) {
                $table->dropColumn('can_be_sold');
            }
        });
    }
};
