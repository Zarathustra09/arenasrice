<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ingredient extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['sku', 'name', 'description', 'stock', 'low_stock_threshold', 'image']; // Add 'low_stock_threshold'

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['sku', 'name', 'description', 'stock', 'low_stock_threshold', 'image']) // Add 'low_stock_threshold'
            ->useLogName('ingredient')
            ->logOnlyDirty();
    }
}
