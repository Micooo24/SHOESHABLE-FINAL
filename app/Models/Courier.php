<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'couriers';

    protected $fillable = [
        'courier_name',
        'contact_number',
        'email',
        'service_area',
        'img_path'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function order() {
        return $this->hasMany(Order::class);
    }
}
