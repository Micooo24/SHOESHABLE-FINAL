<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'payment_method_id', 'date_of_payment'];

    public function orders() {
        return $this->belongsTo(Order::class);
    }
}
