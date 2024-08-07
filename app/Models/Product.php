<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    use Searchable;

    protected $fillable = ['name', 'brand_id', 'supplier_id', 'description', 'cost', 'img_path'];

    public function stocks()
    {
        return $this->hasOne(Stock::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    // public function orders() {
    //     return $this->belongsToMany(Order::class, 'order_product', 'order_id', 'product_id')->withPivot('quantity');
    // }

    public function orders() {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }

    // public function customers()
    // {
    //     return $this->belongsToMany(Customer::class, 'customer_product', 'customer_id', 'product_id')->withPivot('cart_qty');
    // }

    public function customers()
    {
        return $this->belongsToMany(Customer::class)->withPivot('cart_qty');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}

