<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\ProfileReposiyInterface;

class ProfileService
{
    public function __construct(protected ProfileReposiyInterface $profile){}

    public function show($request) {
       $profile = $this->profile->show($request);
       if ($profile) {
        return ApiResponse::sendResponse('profile data retrived success',200,[
            "name" => $profile->name ?? null,
            "email" => $profile->email ?? null,
            "image" => $profile?->image ? asset('storage/'.$profile->image) : null,
            "address" => $profile->address ?? null
        ]);
       }
       return ApiResponse::sendResponse('user haven\'t profile',422 );
    }

    public function update($request,$user) {
        $profile = $user->profile()->first();
        if (isset($request['current_password'])) {
            $valid = Hash::check($request['current_password'], $user->password);
            if (!$valid) {
                return ApiResponse::sendResponse('invalid password', 422);
            }
            $request['password'] = Hash::make($request['password']);
            $user->update([
                'password' => $request['password'],
                'email' => $request['email'],
            ]);
        }

        if ($request['image']) {
            if ($profile && $profile->image) {
                Storage::delete($profile->image);
            }
            $request['image'] = Storage::putFile('profile', $request['image']);
        } else {
            $request['image'] = $profile?->image ?? null;
        }

        if ($profile) {
           $profile =  $this->profile->update($profile,$request);
        } else {
            $profile = $user->profile()->create($request);
        }

        return ApiResponse::sendResponse('profile update success', 200, [
            'name' => $profile->name ?? null,
            "email" => $profile->email ?? null,
            "phone" => $profile->phone ?? null,
            "address" => $profile->address ?? null,
            "image" => $profile->image ? asset('storage/'.$profile->image) : null,
        ]);
    }

    public function destroy($request,$user) {
        if ($user->profile) {
            $user->currentAccessToken()->delete();
            $user->delete();
            return ApiResponse::sendResponse('acount deleted success',200);
        }
        return ApiResponse::sendResponse('user haven\'t prfile',422);
    }
}
