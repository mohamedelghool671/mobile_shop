<?php

namespace App\Interfaces;

interface FavouriteInterface
{
    public function getFavouritesForUser($userId);
    public function add($userId, $productId);
    public function remove($userId, $productId);
}
