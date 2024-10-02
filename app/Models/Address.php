<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'address_line1', 'address_line2', 'city', 'state', 'zip_code', 'country'];

    // Relationship: An address belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
