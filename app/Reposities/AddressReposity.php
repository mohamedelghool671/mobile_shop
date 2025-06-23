<?php

namespace App\Reposities;

use App\Interfaces\AddressReposityInterface;
use App\Models\Address;

class AddressReposity implements AddressReposityInterface
{
    public function store($data) {
            return Address::create($data->toArray());
    }

    public function show() {
        return Address::where('user_id',auth()->id())->get();
    }

    public function update($data,$addres_id) {
        $addres = Address::where([
            ['user_id',$data->user_id],
            ['id',$addres_id]
        ])->first();
        if ($addres) {
            return tap($addres,function($addres) use ($data) {
                return $addres->update($data->toArray());
            });
        }
    }

    public function delete($addres_id) {
        $model = Address::where([
            ['user_id',auth()->id()],
            ['id',$addres_id]
        ])->first();
        if ($model) {
            return $model->delete();
        }
    }
}
