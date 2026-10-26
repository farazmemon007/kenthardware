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
        if (!Schema::hasTable('price_approval_requests')) {
            Schema::create('price_approval_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
                $table->string('variant_key')->nullable();
                $table->string('item_name');
                $table->string('sales_desc')->nullable();
                $table->decimal('qty', 12, 3)->default(1);
                $table->decimal('regular_price', 12, 2)->default(0);
                $table->decimal('root_price', 12, 2)->default(0);
                $table->decimal('requested_price', 12, 2)->default(0);
                $table->decimal('discount_below_root', 12, 2)->default(0);
                $table->enum('status', ['pending', 'approved', 'rejected', 'used', 'cancelled'])->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->text('admin_notes')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamp('responded_at')->nullable();
                $table->timestamps();

                $table->index(['status', 'created_at']);
                $table->index(['user_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_approval_requests');
    }
};
