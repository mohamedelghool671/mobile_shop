<?php

namespace App\Interfaces;

interface UserReposityInterface
{
    public function create($data);

    public function verfiy($data);

    public function resendVerfication($data);

    public function forgetPassword($data,$email);

    public function checkResetCode($data);

    public function resetPassword($data,$email);
}
