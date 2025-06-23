<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\DeviceToken;
use Illuminate\Support\Str;
use App\Jobs\ForgetPasswordJob;
use App\Events\RegisterVerfiyEvent;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Interfaces\UserReposityInterface;

class UserService
{
    public function __construct(protected UserReposityInterface $userReposity){}

    public function create($data) {
        $data['password'] = bcrypt($data['password']);
        $data['email_verfiy_code'] = $this->generateCode();
        $data['email_verfiy_code_expires_at'] = now()->addMinutes(10);
        $user = $this->userReposity->create($data->toArray());
        // create fcm token
       $this->createDeviceToken($user->id,$data->token,);
        return [
            'email' => $user->email
        ];
    }

    public function login($data) {
        if (Auth::attempt(["email" => $data->email, "password" => $data->password])) {
            $user = Auth::user();
            // create fcm if user used another device
           $this->createDeviceToken($user->id, $data->token);
           $token = $user->createToken('login user')->plainTextToken;
           return $this->return($user,$token);
        }
    }

    public function social($request, $provider) {
        try {
            $access_token = $request->get("access_token");
            $user = Socialite::driver($provider)->stateless()->userFromToken($access_token);
            $data = [
                "user_id" => $user->id,
                "name" => $user->user['given_name'] . $user->user['family_name'] ?? $user['name'] ?? "Unknown",
                "email" => $user->email,
                "redirect" => "dashboard/$user->role",
                "provider_id" => $user->id
            ];
            $newUser = $this->userReposity->create($data);
            // create fcm token
            $this->createDeviceToken($newUser->id,$request->token);
            return $this->return($newUser);
        } catch (Exception $e) {
            return false;
        }
    }

    public function forget($data) {
        $resetCode = $this->generateCode();
        $enter_data = [
            'reset_code' => $resetCode,
            'reset_code_expires_at' => Carbon::now()->addMinute(5)
        ];
        $user = $this->userReposity->forgetPassword($data->email, $enter_data);
        if ($user) {
            dispatch(new ForgetPasswordJob($user, $resetCode))->withoutDelay();
            return ['email' => $user->email];
        }
    }

    public function checkResetCode($data) {
        $user = $this->userReposity->checkResetCode($data);
        if ($user && $user->reset_code_expires_at > now()) {
            $token = Str::random(70);
            $user->update([
                'reset_code_token' => $token,
                'reset_code' => null,
                'reset_code_expires_at' => null
            ]);
            return  [
                'token' => $token
            ];
        }
    }

    public function reset($data) {
        $enter_data = [
            'password' => bcrypt($data->password),
            'reset_code_token' => null,
        ];
        $user = $this->userReposity->resetPassword($enter_data, $data->toArray());
        if ($user) {
            $user->tokens()->delete();
            return true;
        }
    }

    public function verfiy($data) {
        return $this->userReposity->verfiy($data);
    }

    public function resendVerfication($data) {
        $data['code'] = $this->generateCode();
        $user = $this->userReposity->resendVerfication($data);
        if ($user) {
             event(new RegisterVerfiyEvent($user));
             return true;
        }
    }


    // helper functions
    protected function generateCode(){
        return rand(100000, 999999);
    }

     protected function return($user,$token) {
        return  [
            "user_name" => $user->name,
            "email" => $user->email,
            "redirect" => "dashboard/$user->role",
            "token" =>$token
        ];
    }

    protected function createDeviceToken($userId,$token) {
         DeviceToken::updateOrCreate(
                ['token' => $token],
                ['user_id' => $userId]
            );
    }
}