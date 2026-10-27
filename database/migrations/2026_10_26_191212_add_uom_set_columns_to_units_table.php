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
        Schema::table('units', function (Blueprint $table) {
            if (!Schema::hasColumn('units', 'base_unit')) {
                $table->string('base_unit')->nullable()->after('name');
            }
            if (!Schema::hasColumn('units', 'unit_type')) {
                $table->string('unit_type')->nullable()->default('Count')->after('base_unit');
            }
            if (!Schema::hasColumn('units', 'uom_data')) {
                $table->json('uom_data')->nullable()->after('unit_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['base_unit', 'unit_type', 'uom_data']);
        });
    }
};
