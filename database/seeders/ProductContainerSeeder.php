<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductContainer;

class ProductContainerSeeder extends Seeder
{
    public function run()
    {
        ProductContainer::create(['name' => 'Box', 'quantity' => 100, 'low_stock_threshold' => 20]);
        ProductContainer::create(['name' => 'Bag', 'quantity' => 200, 'low_stock_threshold' => 50]);
    }
}
