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
        Schema::create('storage_locations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['warehouse_rack', 'shop_shelf'])->default('warehouse_rack');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('zone_or_aisle')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('creater_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('warehouse_id');
            $table->index('branch_id');
            $table->index('type');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_locations');
    }
};
