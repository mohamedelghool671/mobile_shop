<?php

namespace App\Interfaces;

interface UserReposityInterface
{
    public function create($request);

    public function login($request);

    public function logout($request);

    public function forgetPassword($request,$email);

    public function checkResetCode($request);

    public function resetPassword($request,$email);
}
