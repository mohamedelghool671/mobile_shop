<?php

namespace App\Reposities;

use App\Interfaces\CobonReposityInterface;
use App\Models\Cobon;

class CobonReposity implements CobonReposityInterface
{

        protected function clear() {
            $cobons = Cobon::where('number_uses',0)->orWhere('expire_date','<',now())->get();
            foreach($cobons as $cobon) {
                $cobon->delete();
            }
        }

        public function all() {
            $this->clear();
            return Cobon::where([
                ['number_uses', '!=',0],
                ['expire_date','>',now()]
            ])->get();
        }

    public function store($data) {
        return Cobon::create($data);
    }

    public function update($data,$cobonId) {
        $cobon = Cobon::find($cobonId);
        if ($cobon) {
            return tap($cobon,function($cobon) use ($data) {
                return $cobon->update($data);
            });
    }
    }

    public function delete($cobonId) {
         $cobon = Cobon::find($cobonId);
         if ($cobon) {
            return $cobon->delete();
         }
    }

    public function check($cobon) {
        $userId = auth()->id();
        $cobon = Cobon::where([
            ['cobon',$cobon],
            ['number_uses','!=',0],
            ['expire_date','>',now()]
        ])->first();
        if ($cobon) {
            $used = $cobon->users()->where('user_id',$userId)->exists();
            if ($used) {
                return false;
            }
            $cart = new CartReposity();
            $cart = $cart->all();
            if (!$cart) {
                return false;
            }
            $items = $cart->items;
            $cobon->decrement('number_uses');
            $cobon->users()->syncWithoutDetaching([
                $userId => ['created_at' => now()]
            ]);
            $discount = $cobon->value / 100;
             foreach ($items as $item) {
            $originalPrice = $item->price;
            $discountedPrice = round($originalPrice * (1 - $discount), 2);
            $item->update([
                'price' => $discountedPrice
            ]);
    }
    return $cobon;
}
}
}
