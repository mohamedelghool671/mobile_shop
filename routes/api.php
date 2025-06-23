<?php



use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\AddressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PayController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CobonController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\MessageController;
use App\Models\Cobon;
use App\Models\User;

Route::middleware(["auth:sanctum"])->group(function () {
    // comment Route
    Route::apiResource('comments',CommentController::class);
    // add method latest comments to CommentController
    Route::get("latest-comments",[CommentController::class,"latestComments"]);
    // cart
    Route::apiResource('cart',CartController::class);
    Route::put('/cart',[CartController::class,'update']);
    //  order
    Route::apiResource('orders',OrderController::class);
    Route::get('/order',[OrderController::class,'show']);
    // contact
    Route::post('/contact',[ContactController::class,'contact']);
    Route::get('/contact/all',[ContactController::class,'all']);
    Route::post('/contact/response',[ContactController::class,'response']);
    // profile
    Route::put('profile',[ProfileController::class,'update']);
    Route::get('profile',[ProfileController::class,'show']);
    Route::delete('profile',[ProfileController::class,'destroy']);

    // favourite Route
    Route::apiResource('favourites', FavouriteController::class);
    Route::controller(PayController::class)->group(function () {
        Route::post('/pay','pay');
        Route::any('/pay/webhook','hook');
    });

    // Address route
    Route::put('address/{addres_id}',[AddressController::class,'updateAddress']);
    Route::delete('address/{addres_id}',[AddressController::class,'deleteAddress']);
    Route::apiResource('address',AddressController::class)->except(['update','show']);

    // cobon route
    Route::post('cobons/check',[CobonController::class,'check']);
    Route::apiResource('cobons',CobonController::class)->except('show');

    // notification route
    Route::get('notification',[NotificationController::class,'all']);
    Route::delete('notification',[NotificationController::class,'clear']);
});
// product visit
Route::get('/products/visited-products', [ProductController::class, 'userVisitedProducts']);
Route::get('/products/most-visited-products', [ProductController::class, 'mostVisitedProducts']);
// product search
Route::get('products/search', [ProductController::class,'search']);
// product Route
Route::get('products/latest', [ProductController::class,'latest']);
Route::apiResource('products', ProductController::class);
// category Route
Route::apiResource('categories', CategoryController::class);



Route::fallback(function() {
    return ApiResponse::sendResponse('page not found',404);
});

Route::post('/message',[MessageController::class,'store']);


Route::get('learn',function() {

});
require __DIR__ . "/Api/auth.php";

