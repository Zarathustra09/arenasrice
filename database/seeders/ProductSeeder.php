<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create(['name' => 'Chocolate Cake', 'price' => 500, 'stock' => 20, 'low_stock_threshold' => 5, 'category_id' => 1]);
        Product::create(['name' => 'Croissant', 'price' => 50, 'stock' => 100, 'low_stock_threshold' => 20, 'category_id' => 2]);
        Product::create(['name' => 'Baguette', 'price' => 30, 'stock' => 50, 'low_stock_threshold' => 10, 'category_id' => 3]);
    }
}
