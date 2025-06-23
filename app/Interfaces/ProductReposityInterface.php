<?php

namespace App\Interfaces;

interface ProductReposityInterface
{
    public function all($limit,$category_id);

    public function store($data);

    public function update($product,$data);

    public function delete($product);

    public function latest();

    public function userVisite();

    public function allVisite();

    public function search($query);
}
