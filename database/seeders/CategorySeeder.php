<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Cakes', 'description' => 'Various types of cakes']);
        Category::create(['name' => 'Pastries', 'description' => 'Delicious pastries']);
        Category::create(['name' => 'Breads', 'description' => 'Freshly baked breads']);
    }
}
