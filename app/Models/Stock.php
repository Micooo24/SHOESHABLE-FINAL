<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','quantity'];
    protected $primaryKey = 'product_id';

    public $incrementing = false;
    protected $table = 'stocks';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}