<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    Protected $fillable = [
        "product_name","total_price","order_id","product_image","quantity"
    ];



    public function order()
{
    return $this->belongsTo(Order::class);
}
}
