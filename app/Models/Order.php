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
        'billing_name',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_phone',
        'billing_email'
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
}
