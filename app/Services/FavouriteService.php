<?php

namespace App\Services;

use App\Http\Resources\FavouriteResource;
use App\Interfaces\FavouriteInterface;

class FavouriteService
{
  protected $favouriteRepo;

    public function __construct(FavouriteInterface $favouriteRepo)
    {
        $this->favouriteRepo = $favouriteRepo;
    }

    public function getFavourites()
    {
        return $this->favouriteRepo->getFavouritesForUser();
    }

    public function addToFavourites($productId)
    {
        return $this->favouriteRepo->add($productId);
    }

    public function removeFromFavourites($productId)
    {
        return $this->favouriteRepo->remove($productId);
    }
}