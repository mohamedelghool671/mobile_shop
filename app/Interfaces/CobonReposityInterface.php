<?php

namespace App\Interfaces;

interface CobonReposityInterface
{
    public function all();

    public function store($data);

    public function update($data,$cobonId);

    public function delete($cobonId);

    public function check($cobon);
}
