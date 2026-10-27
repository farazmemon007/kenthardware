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
        Schema::table('sale_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_returns', 'carpenter_deduction_settled')) {
                $table->boolean('carpenter_deduction_settled')->default(0)->after('carpenter_deduction_amount')->index();
            }
            if (!Schema::hasColumn('sale_returns', 'carpenter_settled_commission_id')) {
                $table->unsignedBigInteger('carpenter_settled_commission_id')->nullable()->after('carpenter_deduction_settled')->index();
            }
            if (!Schema::hasColumn('sale_returns', 'carpenter_settled_at')) {
                $table->timestamp('carpenter_settled_at')->nullable()->after('carpenter_settled_commission_id');
            }
        });

        Schema::table('carpenter_commissions', function (Blueprint $table) {
            if (!Schema::hasColumn('carpenter_commissions', 'return_deduction_amount')) {
                $table->decimal('return_deduction_amount', 10, 2)->default(0)->after('commission_amount');
            }
            if (!Schema::hasColumn('carpenter_commissions', 'net_payable_amount')) {
                $table->decimal('net_payable_amount', 10, 2)->default(0)->after('return_deduction_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carpenter_commissions', function (Blueprint $table) {
            if (Schema::hasColumn('carpenter_commissions', 'net_payable_amount')) {
                $table->dropColumn('net_payable_amount');
            }
            if (Schema::hasColumn('carpenter_commissions', 'return_deduction_amount')) {
                $table->dropColumn('return_deduction_amount');
            }
        });

        Schema::table('sale_returns', function (Blueprint $table) {
            if (Schema::hasColumn('sale_returns', 'carpenter_settled_at')) {
                $table->dropColumn('carpenter_settled_at');
            }
            if (Schema::hasColumn('sale_returns', 'carpenter_settled_commission_id')) {
                $table->dropColumn('carpenter_settled_commission_id');
            }
            if (Schema::hasColumn('sale_returns', 'carpenter_deduction_settled')) {
                $table->dropColumn('carpenter_deduction_settled');
            }
        });
    }
};
