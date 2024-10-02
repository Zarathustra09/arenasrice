<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_id', 'checkout_link', 'external_id', 'status'];

    // Relationship: A payment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A payment belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
