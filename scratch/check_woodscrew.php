<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Product::where('item_name', 'like', '%wood screw%')->first();
if ($p) {
    echo "ID: " . $p->id . "\n";
    echo "Name: " . $p->item_name . "\n";
    echo "Color JSON:\n" . $p->color . "\n";
} else {
    echo "Product not found\n";
}
