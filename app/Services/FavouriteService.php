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
        $userId = auth()->id();
        return FavouriteResource::collection($this->favouriteRepo->getFavouritesForUser($userId));
    }

    public function addToFavourites($productId)
    {
        $userId = auth()->id();
        return $this->favouriteRepo->add($userId, $productId);
    }

    public function removeFromFavourites($favouriteId)
    {
        $userId = auth()->id();
        return $this->favouriteRepo->remove($userId, $favouriteId);
    }
}