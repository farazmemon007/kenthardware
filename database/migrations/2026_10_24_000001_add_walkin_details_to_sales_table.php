<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'walkin_phone')) {
                $table->string('walkin_phone', 50)->nullable()->after('walkin_name');
            }
            if (!Schema::hasColumn('sales', 'walkin_address')) {
                $table->text('walkin_address')->nullable()->after('walkin_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'walkin_phone')) {
                $table->dropColumn('walkin_phone');
            }
            if (Schema::hasColumn('sales', 'walkin_address')) {
                $table->dropColumn('walkin_address');
            }
        });
    }
};
