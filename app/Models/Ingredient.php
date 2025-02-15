<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ingredient extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['sku', 'name', 'description', 'stock', 'image'];

    // Configure the activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['sku', 'name', 'description', 'stock', 'image'])
            ->useLogName('ingredient')
            ->logOnlyDirty();
    }
}
