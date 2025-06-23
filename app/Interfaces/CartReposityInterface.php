<?php

namespace App\Interfaces;

interface CartReposityInterface
{
    public function all();

    public function create($data);

    public function find($userId);
}
