<?php

namespace App\Interfaces;

interface AddressReposityInterface
{
    public function store($data);

    public function show();

    public function update($data,$addres_id);

    public function delete($addres_id);
}
