<?php

namespace App\Reposities;

use App\Interfaces\ProfileReposiyInterface;

class ProfileReposity implements ProfileReposiyInterface
{
    public function show() {
       return auth()->user()->profile;
    }
    
    public function update($profile,$data) {
        return tap ($profile,function($profile) use ($data) {
            $profile->update($data);
    });
}
}
