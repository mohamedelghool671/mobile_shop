<?php

namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\VerifyEmailRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\CheckResetCodeRequest;
use App\Http\Requests\Api\ForgetPasswordRequest;

class AuthController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function register(RegisterRequest $request) {
        $data =  $this->userService->create(fluent($request->validated()));
        return $data ? ApiResponse::sendResponse("Register Successfully please verify your email and make login", 201, $data) :
        ApiResponse::sendResponse('error while register',422);
    }

    public function login(LoginRequest $request) {
       $data = $this->userService->login(fluent($request->validated()));
       return $data ? ApiResponse::sendResponse("login success",200,$data):
       ApiResponse::sendResponse("Invalid credentials", 401);
    }
    public function verfiy(VerifyEmailRequest $request) {
        $return_data = $this->userService->verfiy(fluent($request->validated()));
         return $return_data ? ApiResponse::sendResponse('Email Verified success', 200) :
        ApiResponse::sendResponse('User not found or Your verification code has expired please send new code', 422);
    }

    public function resendVerfication(VerifyEmailRequest $request) {
        $return_data = $this->userService->resendVerfication(fluent($request->validated()));
        return $return_data ? ApiResponse::sendResponse("Verification code resend success", 200):
         ApiResponse::sendResponse("User not found orThis email is already activated", 422);  
    }


    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse("Logout success");
    }

    public function socialLogin(Request $request, $provider) {
      $return_data = $this->userService->social($request,$provider);
      return $return_data ? ApiResponse::sendResponse("login success", 200,$return_data) :
      ApiResponse::sendResponse("Invalid token", 422, []);
    }

    public function forgetPassword(ForgetPasswordRequest $request) {
        $return_data = $this->userService->forget(fluent($request->validated()));
        return $return_data ?  ApiResponse::sendResponse("Reset code sent successfully", 200, $return_data):
        ApiResponse::sendResponse("User not found", 404);
    }

    public function checkResetCode(CheckResetCodeRequest $request) {
        $return_data =  $this->userService->checkResetCode($request->validated());
        return $return_data ? ApiResponse::sendResponse("Reset code is valid", 200,$return_data) :
        ApiResponse::sendResponse("Reset code is invalid or expired", 422);
    }

    public function resetPassword(ResetPasswordRequest $request) {
       $return_data = $this->userService->reset(fluent($request->validated()));
        return $return_data ? ApiResponse::sendResponse("Password reset successfully please login", 200) :
        ApiResponse::sendResponse("User not found or reset code expired", 404);
    }
}
