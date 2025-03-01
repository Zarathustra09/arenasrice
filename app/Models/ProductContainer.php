<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductContainer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quantity', 'low_stock_threshold'];

    // Relationship: A container can have many products
    public function products()
    {
        return $this->hasMany(Product::class, 'container_id');
    }
}
