<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Events\Register;
use App\Events\RegisterVerfiyEvent;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Jobs\ForgetPasswordJob;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Interfaces\UserReposityInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(protected UserReposityInterface $userReposity){}

    public function return($user) {
        return ApiResponse::sendResponse("Login success", 200, [
            "user_name" => $user->name,
            "email" => $user->email,
            "redirect" => "dashboard/$user->role",
            "token" => $this->userReposity->login($user)
        ]);
    }

    public function create($data) {
        $data['password'] = bcrypt($data['password']);
        $data['email_verfiy_code'] = $this->generateCode();
        $data['email_verfiy_code_expires_at'] = now()->addMinutes(10);
        $user = $this->userReposity->create($data);
        $user->profile()->create([
            'name' => $user->name,
            "email" => $user->email,
        ]);
        return ApiResponse::sendResponse("Register Successfully please verify your email and make login", 201, [
            'email' => $user->email
        ]);
    }

    public function login($data) {
        if (Auth::attempt(["email" => $data['email'], "password" => $data['password']])) {
            $user = Auth::user();
            return $this->return($user);
        }
        return ApiResponse::sendResponse("Invalid credentials", 401);
    }

    public function logout($data) {
        $this->userReposity->logout($data);
        return ApiResponse::sendResponse("Logout success", 200);
    }

    public function social($request, $provider) {
        try {
            $access_token = $request->get("access_token");
            $user = Socialite::driver($provider)->stateless()->userFromToken($access_token);
            if (!$user) {
                return ApiResponse::sendResponse("User not found", 404, []);
            }
            $data = [
                "user_id" => $user->id,
                "name" => $user->user['given_name'] . $user->user['family_name'] ?? $user['name'] ?? "Unknown",
                "email" => $user->email,
                "redirect" => "dashboard/$user->role",
                "provider_id" => $user->id
            ];
            $newUser = $this->userReposity->create($data);
            $newUser->profile()->create([
                'name' => $user->name,
                "email" => $user->email,
            ]);
            return $this->return($newUser);
        } catch (Exception $e) {
            return ApiResponse::sendResponse("Invalid token", 422, []);
        }
    }

    public function forget($request) {
        $resetCode = $this->generateCode();
        $data = [
            'reset_code' => $resetCode,
            'reset_code_expires_at' => Carbon::now()->addMinute(5)
        ];
        $user = $this->userReposity->forgetPassword($request['email'], $data);
        if ($user) {
            dispatch(new ForgetPasswordJob($user, $resetCode))->withoutDelay();
            return ApiResponse::sendResponse("Reset code sent successfully", 200, ['email' => $user->email]);
        }
        return ApiResponse::sendResponse("User not found", 404);
    }

    public function checkResetCode($request) {
        $user = $this->userReposity->checkResetCode($request);
        if ($user && $user->reset_code_expires_at > now()) {
            $token = Str::random(70);
            $user->update([
                'reset_code_token' => $token,
                'reset_code' => null,
                'reset_code_expires_at' => null
            ]);
            return ApiResponse::sendResponse("Reset code is valid", 200, [
                'token' => $token
            ]);
        }
        return ApiResponse::sendResponse("Reset code is invalid or expired", 422);
    }

    public function reset($request) {
        $data = [
            'password' => bcrypt($request['password']),
            'reset_code_token' => null,
        ];
        $user = $this->userReposity->resetPassword($data, $request);
        if ($user) {
            $user->tokens()->delete();
            return ApiResponse::sendResponse("Password reset successfully please login", 200);
        }
        return ApiResponse::sendResponse("User not found or reset code expired", 404);
    }

    public function verfiy($data) {
        $code = $data['email_verfiy_code'];
        $user = User::where('email_verfiy_code', $code)->first();
        if ($user) {
            if ($user->email_verfiy_code_expires_at > now()) {
                $user->update([
                    'email_verfiy_code_expires_at' => null,
                    'email_verfiy_code' => null,
                    'email_verified_at' => now()
                ]);
                return ApiResponse::sendResponse('Email Verified success', 200);
            }
            return ApiResponse::sendResponse('Your verification code has expired please send new code', 422);
        }
        return ApiResponse::sendResponse('User not found', 404);
    }

    public function resendVerfication($data) {
        $user = User::where('email', $data['email'])->first();
        if ($user) {
            if ($user->email_verified_at === null) {
                $user->update([
                    'email_verfiy_code' => $this->generateCode(),
                    'email_verfiy_code_expires_at' => now()->addMinutes(10)
                ]);
                event(new RegisterVerfiyEvent($user));
                return ApiResponse::sendResponse("Verification code resend success", 200);
            }
            return ApiResponse::sendResponse("This email is already activated", 422);
        }
        return ApiResponse::sendResponse('User not found', 404);
    }

    public function generateCode(){
        return rand(100000, 999999);
    }
}