<?php

namespace App\Services;

use App\Helpers\SendNotification;
use App\Interfaces\CobonReposityInterface;

class CobonService
{
    public function __construct(public CobonReposityInterface $cobon)
    {
    }

      public function all() {
        return $this->cobon->all();
      }

    public function store($data) {
        $cobon =  $this->cobon->store($data->toArray());
        SendNotification::sendAll("New cobon : $cobon->name","About this cobon : $cobon->desc",null);
        return $cobon;
    }

    public function update($data,$cobonId) {
        return $this->cobon->update($data->toArray(),$cobonId);
    }

    public function delete($cobonId) {
        return $this->cobon->delete($cobonId);
    }

    public function check($cobon) {
        return $this->cobon->check($cobon);
    }
}
