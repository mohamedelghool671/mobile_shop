<?php

namespace App\Reposities;

use App\Interfaces\ProfileReposiyInterface;

class ProfileReposity implements ProfileReposiyInterface
{
    public function show($request) {
        $profile = $request->user()->profile;
        return $profile;
    }
    public function update($profile,$request) {
        $profile->update($request);
        return $profile;
    }
}
