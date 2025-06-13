<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    protected $fillable = [
        'price','quantity','cart_id','product_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
