<?php

namespace App\Interfaces;

interface FavouriteInterface
{
    public function getFavouritesForUser();
    public function add($productId);
    public function remove($productId);
}
