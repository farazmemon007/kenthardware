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
            if (!Schema::hasColumn('products', 'cycle_count_days')) {
                $table->unsignedInteger('cycle_count_days')->default(30)->after('is_active');
            }
            if (!Schema::hasColumn('products', 'cycle_count_class')) {
                $table->char('cycle_count_class', 1)->default('C')->after('cycle_count_days');
            }
            if (!Schema::hasColumn('products', 'cycle_count_frequency_mode')) {
                $table->string('cycle_count_frequency_mode', 20)->default('auto')->after('cycle_count_class');
            }
            if (!Schema::hasColumn('products', 'last_counted_at')) {
                $table->dateTime('last_counted_at')->nullable()->after('cycle_count_frequency_mode');
            }
            if (!Schema::hasColumn('products', 'next_count_date')) {
                $table->date('next_count_date')->nullable()->index()->after('last_counted_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'cycle_count_days',
                'cycle_count_class',
                'cycle_count_frequency_mode',
                'last_counted_at',
                'next_count_date',
            ]);
        });
    }
};
