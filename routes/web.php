<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Api\PayController;

Route::any('/hook',[PayController::class,'hook']);

Route::get('/',function() {
    abort(403,'page not found');
})->middleware("throttle:1,1");

Route::view('listen','welcome');
Broadcast::routes();

