<?php

namespace App\Interfaces;

interface CategoryReposityInterface
{
    public function all($limit);

    public function create($data);

    public function update($data,$category);

}
