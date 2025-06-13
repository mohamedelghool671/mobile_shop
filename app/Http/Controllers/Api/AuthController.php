<?php

namespace App\Http\Controllers\Api;


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

        return $this->userService->create($request->validated());
    }

    public function verfiy(VerifyEmailRequest $request) {
        return $this->userService->verfiy($request->validated());
    }

    public function resendVerfication(VerifyEmailRequest $request) {
        return $this->userService->resendVerfication($request->validated());
    }

    public function login(LoginRequest $request) {
       return $this->userService->login($request->validated());
    }

    public function logout(Request $request) {
        return $this->userService->logout($request);
    }

    public function socialLogin(Request $request, $provider) {
      return $this->userService->social($request,$provider);
    }

    public function forgetPassword(ForgetPasswordRequest $request) {
        return $this->userService->forget($request->validated());
    }

    public function checkResetCode(CheckResetCodeRequest $request) {
        return $this->userService->checkResetCode($request->validated());
    }

    public function resetPassword(ResetPasswordRequest $request) {
       return $this->userService->reset($request->validated());
    }
}
