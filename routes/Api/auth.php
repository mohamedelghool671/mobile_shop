<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Api\AuthController;

Route::controller(AuthController::class)->group(function() {
    Route::post("register","register");
    Route::post("logout","logout")->middleware('auth:sanctum');
    Route::post("login/{provider}/callback","socialLogin");
    Route::post("verfiy","verfiy");
    Route::post("resendVerification","resendVerfication");
    Route::post("login","login")->middleware('verfiy_email');
    Route::post("forget-password","forgetPassword");
    Route::post("checkResetCode","checkResetCode");
    Route::post("reset-password","resetPassword");
});


