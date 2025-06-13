<?php

namespace App\Reposities;

use App\Interfaces\FavouriteInterface;
use App\Models\Product;
use App\Models\Favourite;

class FavouriteReposity implements FavouriteInterface
{
  public function getFavouritesForUser($userId)
    {
        return Favourite::where('user_id',$userId)->get();
    }

    public function add($userId, $productId)
    {
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

    public function remove($userId, $favouriteId)
    {
        $favourite = Favourite::find($favouriteId);
        if ($favourite) {
            $favourite->delete();
            return true;
        }
    }
}

