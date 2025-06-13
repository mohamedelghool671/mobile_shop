<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        "user_id","status","user_first_name","user_last_name","phone","email","city","governorate",
        "address","country","postal_code","gift_recipient_phone","gift_recipient_name","gift_recipient_city",
        "gift_recipient_governorate","gift_recipient_address","gift_recipient_country","gift_recipient_postal_code"
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
