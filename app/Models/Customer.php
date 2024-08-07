<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'username', 'address', 'contact_number'];

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'customer_product', 'customer_id', 'product_id')->withPivot('cart_qty');
    // }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('cart_qty');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
