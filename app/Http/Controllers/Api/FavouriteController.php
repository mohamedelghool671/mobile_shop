<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\FavouriteService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FavouriteRequest;

class FavouriteController extends Controller
{
    protected $favouriteService;

    public function __construct(FavouriteService $favouriteService)
    {
        $this->favouriteService = $favouriteService;
    }

    public function index()
    {
        return ApiResponse::sendResponse('favourites retrived succes',200,$this->favouriteService->getFavourites());
    }

    public function store(FavouriteRequest $request)
    {
        return $this->favouriteService->addToFavourites($request->validated()['product_id'])?
        ApiResponse::sendResponse('product add to favourite') :
        ApiResponse::sendResponse('already exist',404);
    }

    public function destroy($favouriteId)
    {
        return $this->favouriteService->removeFromFavourites($favouriteId) ?
        ApiResponse::sendResponse('this field deleted success') : ApiResponse::sendResponse('field does not exist');
    }
}
