<?php

namespace App\Reposities;

use Carbon\Carbon;
use App\Models\User;
use App\Interfaces\UserReposityInterface;

class UserReposity implements UserReposityInterface
{
    public function create($request) {
        return User::create($request);
    }

    public function login($user) {
        return $user->createToken('login user')->plainTextToken;
    }

    public function logout($request) {
        $request->user()->currentAccessToken()->delete();
    }

    public function find($email) {
        return User::where('email',$email);
    }
    public function forgetPassword($email,$request) {
       $user = $this->find($email)->first();
       if ($user) {
        $user->update($request);
        return $user;
       }
    }

    public function checkResetCode($request) {
        return User::where('reset_code',$request['reset_code'])
        ->where('reset_code_expires_at','>',now())
        ->first();
    }

    public function resetPassword($data,$request) {
        $user = User::where('reset_code_token',$request['reset_code_token'])->first();
        if ($user) {
            $user->update($data);
            return $user;
        }
    }
}
