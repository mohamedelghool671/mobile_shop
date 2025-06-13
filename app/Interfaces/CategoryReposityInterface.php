<?php

namespace App\Interfaces;

interface CategoryReposityInterface
{
    public function all($limit);

    public function create($request);

    public function update($request,$category);

    public function delete($category);
}
