<?php

namespace App\Services;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\ProfileReposiyInterface;

class ProfileService
{
    public function __construct(protected ProfileReposiyInterface $profile){}

    public function show() {
       return $this->profile->show();
    }

    public function update($data) {
        $user = auth()->user();
        $profile = $user->profile()->first();
        // update password
        if ($data->current_password) {
            if (!Hash::check($data->current_password, $user->password)) {
                return false;
            }
            $user->update([
                'password' =>  Hash::make($data->password),
                'email' => $data->email,
            ]);
        }

        if ($data->image) {
            if ($profile && $profile->image) {
                Storage::delete($profile->image);
            }
            $data['image'] = Storage::putFile('profile', $data->image);
        }

        if ($profile) {
           $profile =  $this->profile->update($profile,$data->toArray());
        } else {
            $profile = $user->profile()->create($data->toArray());
        }

        return  [
            'name' => $profile->name ?? null,
            "email" => $profile->email ?? null,
            "phone" => $profile->phone ?? null,
            "address" => $profile->address ?? null,
            "image" => $profile->image ? asset('storage/'.$profile->image) : null,
        ];
    }

    public function destroy() {
        $user = auth()->user();
        if ($user->profile) {
            $user->currentAccessToken()->delete();
            $user->delete();
           return true;
        }
    }
}
