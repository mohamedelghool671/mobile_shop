<?php

namespace App\Reposities;

use App\Interfaces\CartReposityInterface;
use App\Models\Cart;

class CartReposity implements CartReposityInterface
{

    public function find($user_id) {
        return Cart::with('items')->where('user_id',$user_id)->first();
    }
    public function all($request) {
        $cart = $this->find($request->user()->id);
                return $cart;
    }

    public function create($request) {
        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id
        ]);
            return $cart;
    }
}
