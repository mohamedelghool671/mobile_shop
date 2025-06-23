<?php

namespace App\Reposities;


use App\Models\User;
use App\Interfaces\UserReposityInterface;

class UserReposity implements UserReposityInterface
{
    public function create($data) {
        return User::create($data);
    }

    public function find($email) {
        return User::where('email',$email);
    }
    public function forgetPassword($email,$request) {
       $user = $this->find($email)->first();
       if ($user) {
        return tap($user,function($user) use ($request) {
            $user->update($request);
        });
       }
    }

    public function checkResetCode($request) {
        return User::where([
            ['reset_code',$request['reset_code']],
            ['reset_code_expires_at','>',now()]
        ])->first();
    }

    public function resetPassword($data,$request) {
        $user = User::where('reset_code_token',$request['reset_code_token'])->first();
        if ($user) {
            return tap($user,function($user) use ($data) {
                return $user->update($data);
            });
        }
    }

     public function verfiy($data) {
        $user = User::where('email_verfiy_code', $data->email_verfiy_code)->first();
        if ($user) {
            if ($user->email_verfiy_code_expires_at > now()) {
                $user->update([
                    'email_verfiy_code_expires_at' => null,
                    'email_verfiy_code' => null,
                    'email_verified_at' => now()
                ]);
              return true;
            }
     }
    }

    public function resendVerfication($data) {
        $user = $this->find($data->email)->first();
        if ($user) {
            if ($user->email_verified_at === null) {
                return tap($user,function($user) use ($data) {
                    return $user->update([
                        'email_verfiy_code' => $data->code,
                        'email_verfiy_code_expires_at' => now()->addMinutes(10)
                    ]);
                });
            }
        }
    }
}
