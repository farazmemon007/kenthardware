<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\WarehouseStock;
use App\Models\StockMovement;

class RemoveDummyClothingProductsSeeder extends Seeder
{
    /**
     * Exact 17 Dummy Clothing Products seeded by default
     */
    protected array $dummyNames = [
        'Formal Shirt',
        "Men's Classic White Oxford Shirt",
        "Men's Tailored Navy Blazer",
        "Men's Slim Fit Chino Trousers",
        "Men's Casual Denim Jacket",
        "Men's Athletic Pullover Hoodie",
        "Women's Luxury Silk Maxi Dress",
        "Women's Elegant Leather Handbag",
        "Women's Classic Double-Breasted Trench Coat",
        "Women's Cozy Knit Cardigan",
        "Women's Stylish Biker Leather Jacket",
        "Boys' Cotton Polo Shirt",
        "Boys' Activewear Crewneck Set",
        "Boys' Adventure Graphic Tee",
        "Girls' Elegant Floral Summer Dress",
        "Girls' Cozy Cable-Knit Cardigan",
        "Girls' Classic Denim Skirt",
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('----------------------------------------------------');
        $this->command->info('  Starting Dummy Clothing Products Cleanup Seeder  ');
        $this->command->info('----------------------------------------------------');

        // 1. Ensure `color` column is LONGTEXT for upcoming 3,000+ items import
        try {
            DB::statement("ALTER TABLE products MODIFY COLUMN color LONGTEXT NULL;");
            $this->command->info('✔ Verified `products.color` is LONGTEXT (Supports 200+ variants per item).');
        } catch (\Throwable $e) {
            // Silently continue if permissions restrict ALTER
        }

        // 2. Find Dummy Products
        $products = Product::where(function ($query) {
            foreach ($this->dummyNames as $name) {
                $query->orWhere(DB::raw('LOWER(TRIM(item_name))'), strtolower(trim($name)));
            }
        })->get();

        $productIds = $products->pluck('id')->toArray();

        if (empty($productIds)) {
            $this->command->info('ℹ No dummy clothing products found in database (already clean).');
            return;
        }

        $this->command->info("Found " . count($productIds) . " dummy clothing products. Deleting associated records...");

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete warehouse stocks
        $stocksDeleted = WarehouseStock::whereIn('product_id', $productIds)->delete();

        // Delete web images
        $imagesDeleted = 0;
        if (Schema::hasTable('product_web_images')) {
            $imagesDeleted = DB::table('product_web_images')->whereIn('product_id', $productIds)->delete();
        }

        // Delete stock movements
        $movementsDeleted = 0;
        if (Schema::hasTable('stock_movements')) {
            $movementsDeleted = StockMovement::whereIn('product_id', $productIds)->delete();
        }

        // Delete other auxiliary records if exist
        if (Schema::hasTable('stocks')) {
            DB::table('stocks')->whereIn('product_id', $productIds)->delete();
        }
        if (Schema::hasTable('product_discounts')) {
            DB::table('product_discounts')->whereIn('product_id', $productIds)->delete();
        }
        if (Schema::hasTable('wishlists')) {
            DB::table('wishlists')->whereIn('product_id', $productIds)->delete();
        }
        if (Schema::hasTable('cycle_count_items')) {
            DB::table('cycle_count_items')->whereIn('product_id', $productIds)->delete();
        }

        // Force delete dummy products
        $productsDeleted = Product::whereIn('id', $productIds)->forceDelete();

        // Reset auto increment if table is empty
        $autoIncrementReset = false;
        if (Product::count() === 0) {
            DB::statement('ALTER TABLE products AUTO_INCREMENT = 1;');
            $autoIncrementReset = true;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->table(
            ['Entity', 'Count Removed'],
            [
                ['Dummy Products', $productsDeleted],
                ['Warehouse Stock Records', $stocksDeleted],
                ['Product Web Images', $imagesDeleted],
                ['Stock Movements', $movementsDeleted],
                ['Auto-Increment Reset to 1', $autoIncrementReset ? 'Yes (ITEM-0001 ready)' : 'No'],
            ]
        );

        $this->command->info('✔ Dummy clothing products cleanup complete! Ready for Excel list upload.');
        $this->command->info('----------------------------------------------------');
    }
}
