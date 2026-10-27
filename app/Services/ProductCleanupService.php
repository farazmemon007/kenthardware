<?php

namespace App\Services;

use App\Models\Product;
use App\Models\WarehouseStock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductCleanupService
{
    /**
     * Exact 17 Dummy Clothing Products seeded in the original ProductSeeder.
     */
    public static function getDummyClothingProductNames(): array
    {
        return [
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
    }

    /**
     * Remove the 17 dummy clothing products and their related records safely.
     *
     * @return array
     */
    public static function removeDummyClothingProducts(): array
    {
        $dummyNames = self::getDummyClothingProductNames();

        // Match case-insensitively
        $products = Product::where(function ($query) use ($dummyNames) {
            foreach ($dummyNames as $name) {
                $query->orWhere(DB::raw('LOWER(TRIM(item_name))'), strtolower(trim($name)));
            }
        })->get();

        $productIds = $products->pluck('id')->toArray();

        $stocksDeleted = 0;
        $imagesDeleted = 0;
        $movementsDeleted = 0;
        $productsDeleted = 0;
        $autoIncrementReset = false;

        if (!empty($productIds)) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 1. Delete Warehouse Stocks
            $stocksDeleted = WarehouseStock::whereIn('product_id', $productIds)->delete();

            // 2. Delete Product Web Images
            if (Schema::hasTable('product_web_images')) {
                $imagesDeleted = DB::table('product_web_images')->whereIn('product_id', $productIds)->delete();
            }

            // 3. Delete Stock Movements
            if (Schema::hasTable('stock_movements')) {
                $movementsDeleted = StockMovement::whereIn('product_id', $productIds)->delete();
            }

            // 4. Delete Stocks / Discounts / Wishlists if any
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

            // 5. Force Delete Dummy Products
            $productsDeleted = Product::whereIn('id', $productIds)->forceDelete();

            // 6. Reset auto-increment if table is empty
            if (Product::count() === 0) {
                DB::statement('ALTER TABLE products AUTO_INCREMENT = 1;');
                $autoIncrementReset = true;
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        return [
            'products_deleted'     => $productsDeleted,
            'stocks_deleted'       => $stocksDeleted,
            'images_deleted'       => $imagesDeleted,
            'movements_deleted'    => $movementsDeleted,
            'auto_increment_reset' => $autoIncrementReset,
        ];
    }

    /**
     * Check if dummy clothing products currently exist in the database.
     */
    public static function hasDummyProducts(): bool
    {
        $dummyNames = self::getDummyClothingProductNames();
        return Product::where(function ($query) use ($dummyNames) {
            foreach ($dummyNames as $name) {
                $query->orWhere(DB::raw('LOWER(TRIM(item_name))'), strtolower(trim($name)));
            }
        })->exists();
    }
}
