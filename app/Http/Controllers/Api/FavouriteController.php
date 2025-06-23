<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\FavouriteService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FavouriteRequest;
use App\Http\Resources\FavouriteResource;

class FavouriteController extends Controller
{
    public function __construct(protected FavouriteService  $favouriteService)
    {
    }

    public function index()
    {
        $data = $this->favouriteService->getFavourites();
        return $data ?  ApiResponse::sendResponse('favourites retrived succes',200,FavouriteResource::collection($data)):
        ApiResponse::sendResponse("favourite is empty",422);
    }

    public function store(FavouriteRequest $request)
    {
        return $this->favouriteService->addToFavourites($request->validated()['product_id'])?
        ApiResponse::sendResponse('product add to favourite') :
        ApiResponse::sendResponse('already exist',404);
    }

    public function destroy($productId)
    {
        return $this->favouriteService->removeFromFavourites($productId) ?
        ApiResponse::sendResponse('this field deleted success') :
        ApiResponse::sendResponse('field does not exist',404);
    }
}
