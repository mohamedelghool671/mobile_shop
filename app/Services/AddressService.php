<?php

namespace App\Services;

use App\Http\Resources\AddressResource;
use App\Interfaces\AddressReposityInterface;

class AddressService
{
    public function __construct(public AddressReposityInterface $address)
    {
        //
    }

    public function store($data) {
        $created_data = $this->address->store($data);
        if ($created_data) {
           return new AddressResource($created_data);
        }
    }

    public function show() {
        return  $this->address->show();
    }

    public function update($data,$addres_id) {
        return $this->address->update($data,$addres_id);
    }

    public function delete($addres_id) {
       return $this->address->delete($addres_id);
    }

}
