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
        if (!Schema::hasTable('carpenters')) {
            Schema::create('carpenters', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('cnic')->nullable();
                $table->text('address')->nullable();
                $table->string('default_commission_type')->default('percentage'); // percentage or fixed
                $table->decimal('default_commission_rate', 10, 2)->default(0);
                $table->tinyInteger('status')->default(1); // 1 = active, 0 = inactive
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('carpenter_commissions')) {
            Schema::create('carpenter_commissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('carpenter_id')->index();
                $table->unsignedBigInteger('sale_id')->index();
                $table->string('commission_type')->default('percentage');
                $table->decimal('commission_rate', 10, 2)->default(0);
                $table->decimal('sale_amount', 12, 2)->default(0);
                $table->decimal('commission_amount', 10, 2)->default(0);
                $table->decimal('effective_percentage', 8, 4)->default(0);
                $table->string('status')->default('pending'); // pending, approved, paid, cancelled
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('paid_by')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->unsignedBigInteger('payment_account_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'carpenter_id')) {
                $table->unsignedBigInteger('carpenter_id')->nullable()->index()->after('customer_id');
            }
            if (!Schema::hasColumn('sales', 'carpenter_commission_type')) {
                $table->string('carpenter_commission_type', 20)->default('percentage')->after('carpenter_id');
            }
            if (!Schema::hasColumn('sales', 'carpenter_commission_rate')) {
                $table->decimal('carpenter_commission_rate', 10, 2)->default(0)->after('carpenter_commission_type');
            }
            if (!Schema::hasColumn('sales', 'carpenter_commission_amount')) {
                $table->decimal('carpenter_commission_amount', 10, 2)->default(0)->after('carpenter_commission_rate');
            }
            if (!Schema::hasColumn('sales', 'carpenter_commission_approved')) {
                $table->boolean('carpenter_commission_approved')->default(0)->after('carpenter_commission_amount');
            }
        });

        Schema::table('sale_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_returns', 'carpenter_id')) {
                $table->unsignedBigInteger('carpenter_id')->nullable()->after('sale_id');
            }
            if (!Schema::hasColumn('sale_returns', 'carpenter_commission_rate')) {
                $table->decimal('carpenter_commission_rate', 10, 2)->default(0)->after('carpenter_id');
            }
            if (!Schema::hasColumn('sale_returns', 'carpenter_deduction_amount')) {
                $table->decimal('carpenter_deduction_amount', 10, 2)->default(0)->after('carpenter_commission_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            if (Schema::hasColumn('sale_returns', 'carpenter_deduction_amount')) {
                $table->dropColumn('carpenter_deduction_amount');
            }
            if (Schema::hasColumn('sale_returns', 'carpenter_commission_rate')) {
                $table->dropColumn('carpenter_commission_rate');
            }
            if (Schema::hasColumn('sale_returns', 'carpenter_id')) {
                $table->dropColumn('carpenter_id');
            }
        });

        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'carpenter_commission_approved')) {
                $table->dropColumn('carpenter_commission_approved');
            }
            if (Schema::hasColumn('sales', 'carpenter_commission_amount')) {
                $table->dropColumn('carpenter_commission_amount');
            }
            if (Schema::hasColumn('sales', 'carpenter_commission_rate')) {
                $table->dropColumn('carpenter_commission_rate');
            }
            if (Schema::hasColumn('sales', 'carpenter_commission_type')) {
                $table->dropColumn('carpenter_commission_type');
            }
            if (Schema::hasColumn('sales', 'carpenter_id')) {
                $table->dropColumn('carpenter_id');
            }
        });

        Schema::dropIfExists('carpenter_commissions');
        Schema::dropIfExists('carpenters');
    }
};
