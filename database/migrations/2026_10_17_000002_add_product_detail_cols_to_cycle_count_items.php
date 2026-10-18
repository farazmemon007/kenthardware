<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add storage_location_id FK to warehouse_stocks for proper location mapping
        Schema::table('warehouse_stocks', function (Blueprint $table) {
            if (!Schema::hasColumn('warehouse_stocks', 'storage_location_id')) {
                $table->unsignedBigInteger('storage_location_id')->nullable()->after('remarks')->index();
                $table->foreign('storage_location_id')->references('id')->on('storage_locations')->onDelete('set null');
            }
        });

        // Populate storage_location_id from existing remarks text "Rack A-1 (RA-01)"
        $locations = \DB::table('storage_locations')->get(['id', 'name', 'code']);
        $stocks = \DB::table('warehouse_stocks')->whereNotNull('remarks')->get();
        foreach ($stocks as $stock) {
            $rem = strtolower(trim($stock->remarks ?? ''));
            foreach ($locations as $loc) {
                $locName = strtolower(trim($loc->name));
                $locCode = $loc->code ? strtolower(trim($loc->code)) : '';
                if ($rem && (
                    $rem === $locName ||
                    ($locCode && $rem === $locCode) ||
                    str_contains($rem, $locName) ||
                    ($locCode && str_contains($rem, $locCode))
                )) {
                    \DB::table('warehouse_stocks')->where('id', $stock->id)->update(['storage_location_id' => $loc->id]);
                    break;
                }
            }
        }

        // Add extra product detail columns to cycle_count_items
        Schema::table('cycle_count_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cycle_count_items', 'p_code')) {
                $table->string('p_code', 100)->nullable()->after('item_name');
            }
            if (!Schema::hasColumn('cycle_count_items', 'category_name')) {
                $table->string('category_name', 100)->nullable()->after('p_code');
            }
            if (!Schema::hasColumn('cycle_count_items', 'image_path')) {
                $table->string('image_path', 500)->nullable()->after('category_name');
            }
            if (!Schema::hasColumn('cycle_count_items', 'barcode_path')) {
                $table->string('barcode_path', 500)->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('cycle_count_items', 'velocity_class')) {
                // e.g. "A-F-X" combined classification
                $table->string('velocity_class', 20)->nullable()->after('barcode_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cycle_count_items', function (Blueprint $table) {
            $cols = ['p_code', 'category_name', 'image_path', 'barcode_path', 'velocity_class'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('cycle_count_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('warehouse_stocks', function (Blueprint $table) {
            if (Schema::hasColumn('warehouse_stocks', 'storage_location_id')) {
                $table->dropForeign(['storage_location_id']);
                $table->dropColumn('storage_location_id');
            }
        });
    }
};
