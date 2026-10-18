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
        // 1. Add classification columns to products if missing
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'abc_class')) {
                $table->char('abc_class', 1)->nullable()->after('cycle_count_class');
            }
            if (!Schema::hasColumn('products', 'fsn_class')) {
                $table->char('fsn_class', 1)->nullable()->after('abc_class');
            }
            if (!Schema::hasColumn('products', 'xyz_class')) {
                $table->char('xyz_class', 1)->nullable()->after('fsn_class');
            }
            if (!Schema::hasColumn('products', 'risk_priority_score')) {
                $table->unsignedTinyInteger('risk_priority_score')->default(3)->after('xyz_class');
            }
        });

        // 2. Add last_counted_at to storage_locations if missing
        Schema::table('storage_locations', function (Blueprint $table) {
            if (!Schema::hasColumn('storage_locations', 'last_counted_at')) {
                $table->dateTime('last_counted_at')->nullable()->after('zone_or_aisle');
            }
        });

        // 3. Create cycle_counts table
        if (!Schema::hasTable('cycle_counts')) {
            Schema::create('cycle_counts', function (Blueprint $table) {
                $table->id();
                $table->string('count_number', 50)->unique(); // e.g. C-0412
                $table->string('count_type', 50)->default('adhoc'); // fast_item, salepoint_shelves, godown_racks, adhoc
                $table->unsignedBigInteger('location_id')->nullable()->index();
                $table->string('location_name', 150)->nullable();
                $table->string('zone', 100)->nullable();
                $table->unsignedBigInteger('warehouse_id')->nullable()->index();
                $table->unsignedBigInteger('branch_id')->nullable()->index();
                $table->date('count_date')->index();
                $table->string('time_window', 100)->nullable(); // e.g. "09:00 - 11:00"
                $table->unsignedBigInteger('assigned_to')->index(); // Employee/Counter user_id
                $table->unsignedBigInteger('approver_id')->nullable()->index(); // Approver user_id
                $table->text('notes')->nullable();
                $table->string('status', 30)->default('not_started')->index(); // draft, not_started, started, submitted, approved, cancelled, recount_requested
                $table->dateTime('started_at')->nullable();
                $table->dateTime('submitted_at')->nullable();
                $table->unsignedInteger('duration_seconds')->default(0);
                $table->unsignedInteger('total_items')->default(0);
                $table->unsignedInteger('entered_items')->default(0);
                $table->unsignedInteger('matched_items')->default(0);
                $table->unsignedInteger('variance_items')->default(0);
                $table->decimal('total_variance_value', 15, 2)->default(0.00);
                $table->dateTime('approved_at')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable()->index();
                $table->unsignedBigInteger('created_by')->nullable()->index();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('location_id')->references('id')->on('storage_locations')->onDelete('set null');
                $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 4. Create cycle_count_items table
        if (!Schema::hasTable('cycle_count_items')) {
            Schema::create('cycle_count_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('cycle_count_id')->index();
                $table->unsignedBigInteger('product_id')->index();
                $table->string('item_code', 100)->nullable()->index();
                $table->string('item_name', 255);
                $table->string('unit', 50)->nullable();
                $table->string('location_name', 150)->nullable();
                $table->decimal('system_qty', 15, 2)->default(0.00);
                $table->decimal('counted_qty', 15, 2)->nullable();
                $table->decimal('difference', 15, 2)->default(0.00);
                $table->decimal('unit_cost', 15, 2)->default(0.00);
                $table->decimal('variance_value', 15, 2)->default(0.00);
                $table->string('variance_reason', 50)->nullable()->index(); // THEFT, DAMAGE, GIVEN, ENTRY ERROR, MISPLACED
                $table->text('reason_notes')->nullable();
                $table->string('misplaced_location', 150)->nullable();
                $table->string('status', 30)->default('pending')->index(); // pending, matched, needs_reason, ready_to_approve, approved, recount
                $table->text('smart_hint')->nullable();
                $table->boolean('is_entered')->default(false)->index();
                $table->dateTime('entered_at')->nullable();
                $table->timestamps();

                $table->foreign('cycle_count_id')->references('id')->on('cycle_counts')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycle_count_items');
        Schema::dropIfExists('cycle_counts');

        Schema::table('storage_locations', function (Blueprint $table) {
            if (Schema::hasColumn('storage_locations', 'last_counted_at')) {
                $table->dropColumn('last_counted_at');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['abc_class', 'fsn_class', 'xyz_class', 'risk_priority_score']);
        });
    }
};
