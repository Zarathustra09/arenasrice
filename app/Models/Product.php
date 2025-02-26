<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['category_id', 'name', 'description', 'price', 'stock', 'image', 'low_stock_threshold'];

    // Relationship: A product belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: One product can have many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Configure the activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['category_id', 'name', 'description', 'price', 'stock', 'image'])
            ->useLogName('product')
            ->logOnlyDirty();
    }
}
