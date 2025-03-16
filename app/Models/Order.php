<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    // Relationship: An order belongs to a user


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: One order can have many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship: One order can have many payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            do {
                $referenceId = 'HYD-' . mt_rand(10000000, 99999999);
            } while (Order::where('reference_id', $referenceId)->exists());

            $order->reference_id = $referenceId;
        });
    }


}
