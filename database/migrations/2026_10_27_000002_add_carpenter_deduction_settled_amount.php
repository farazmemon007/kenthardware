<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_returns', 'carpenter_deduction_settled_amount')) {
                $table->decimal('carpenter_deduction_settled_amount', 10, 2)->default(0)->after('carpenter_deduction_settled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            if (Schema::hasColumn('sale_returns', 'carpenter_deduction_settled_amount')) {
                $table->dropColumn('carpenter_deduction_settled_amount');
            }
        });
    }
};
