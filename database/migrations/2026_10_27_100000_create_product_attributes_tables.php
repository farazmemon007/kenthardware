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
        if (!Schema::hasTable('product_attributes')) {
            Schema::create('product_attributes', function (Blueprint $table) {
                $table->id();
                $table->string('name', 191)->unique();
                $table->string('display_type', 50)->default('Radio');
                $table->string('creation_mode', 50)->default('Instantly');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_attribute_values')) {
            Schema::create('product_attribute_values', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_attribute_id');
                $table->string('value', 191);
                $table->string('color_hex', 50)->nullable();
                $table->string('image', 255)->nullable();
                $table->decimal('extra_price', 12, 2)->default(0.00);
                $table->boolean('is_custom')->default(false);
                $table->integer('sequence')->default(0);
                $table->timestamps();

                $table->foreign('product_attribute_id')
                      ->references('id')
                      ->on('product_attributes')
                      ->onDelete('cascade');

                $table->unique(['product_attribute_id', 'value'], 'idx_attr_val_unique');
            });
        }

        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'attributes_data')) {
            Schema::table('products', function (Blueprint $table) {
                $table->longText('attributes_data')->nullable()->after('color');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_attribute_values')) {
            Schema::dropIfExists('product_attribute_values');
        }

        if (Schema::hasTable('product_attributes')) {
            Schema::dropIfExists('product_attributes');
        }

        if (Schema::hasTable('products') && Schema::hasColumn('products', 'attributes_data')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('attributes_data');
            });
        }
    }
};
