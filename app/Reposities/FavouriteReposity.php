<?php

namespace App\Reposities;

use App\Interfaces\FavouriteInterface;
use App\Models\Favourite;

class FavouriteReposity implements FavouriteInterface
{
  public function getFavouritesForUser()
    {
        $userId = auth()->id();
        return Favourite::with('product')->where('user_id',$userId)->get();
    }

    public function add($productId)
    {
        $userId = auth()->id();
        $exists = Favourite::where('user_id', $userId)->where('product_id', $productId)->exists();
        if ($exists) {
            return false;
        }
        Favourite::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
        return true ;
    }

    public function remove($productId)
    {
        $userId = auth()->id();
        $favourite = Favourite::where([
            ['product_id',$productId],
            ['user_id',$userId]
        ])->first();
        if ($favourite) {
            $favourite->delete();
            return true;
        }
    }
}

