<?php

namespace App\Reposities;

use App\Interfaces\CartReposityInterface;
use App\Models\Cart;

class CartReposity implements CartReposityInterface
{

    public function find($userId) {
        return Cart::with('items')->where('user_id',$userId)->first();
    }
    public function all() {
       return $this->find(auth()->id());
    }

    public function create($data) {
        return Cart::firstOrCreate([
            'user_id' =>auth()->id()
        ]);
    }
}
