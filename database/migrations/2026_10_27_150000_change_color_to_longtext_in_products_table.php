<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify column color to LONGTEXT to support products with many variants
        DB::statement("ALTER TABLE products MODIFY COLUMN color LONGTEXT NULL;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN color TEXT NULL;");
    }
};
