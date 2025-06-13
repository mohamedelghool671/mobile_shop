<?php

namespace App\Interfaces;

interface ProductReposityInterface
{
    public function all($limit,$category_id);

    public function store($request);

    public function update($product,$request);

    public function delete($product);

    public function latest();

    public function userVisite($request);

    public function allVisite();

    public function search($query);
}
