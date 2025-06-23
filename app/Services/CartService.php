<?php

namespace App\Services;

use App\Models\Product;
use App\Models\CartItems;
use App\Helpers\ApiResponse;
use App\Interfaces\CartReposityInterface;

class CartService
{

    public function __construct(protected CartReposityInterface $cart){ }

    public function all() {
        $cart = $this->cart->all();
        if ($cart) {
            $items = $cart->items()->with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_price' => $item->product->price / 100,
                    'image' => asset('storage/' . $item->product->image),
                    'price' => $item->price / 100,
                    'quantity' => $item->quantity,
                ];
            });
            $shipping = (int) env("shipping");
            if ($items) {
                return [
                    "cart_id" => $cart->id,
                    "items" => $items,
                    "delivery_price" => $shipping ,
                    "order_price" => $items->sum('price'),
                    "total_price" => $items->sum('price') + $shipping
                ];
            }
            return false;
        }
        }

    public function create($data) {
        $cart = $this->cart->create($data);
        $product = Product::find($data->product_id);
        if ($data->quantity < 1) {
            $data->quantity = 1;
        }
        $existingItem = $cart->items()
        ->where('product_id', $data->product_id)
        ->first();
        if ($existingItem) {
            $existingItem->quantity += $data->quantity;
            $existingItem->price = $product->price * $existingItem->quantity;
            $existingItem->save();
        } else {
            $data->cart_id = $cart->id;
            $data->price = $product->price * $data->quantity;
            CartItems::create($data->toArray());
        }
        return [
            "cart_id" => $cart->id
        ];
    }

    public function update($data) {
        $cart = $this->cart->all($data);
        if (!$cart) {
            return false;
        }
        $updatedItems = [];
        foreach ($data['items'] as $entry) {
            $item = $cart->items()
                ->where('id', $entry['item_id'])
                ->first();
            if (!$item) {
                $updatedItems[] = [
                    "item_id" => $entry['item_id'],
                    "status" => "not found in user cart"
                ];
                continue;
            }

            if ($entry['quantity'] < 1) {
                $item->delete();
                $updatedItems[] = [
                    "item_id" => $entry['item_id'],
                    "status" => "deleted"
                ];
                continue;
            }
            $product = Product::find($item->product_id);
            $new_quantity = $entry['quantity'];
            $new_price = $product->price * $new_quantity;

            $item->update([
                "quantity" => $new_quantity,
                "price" => $new_price
            ]);

            $updatedItems[] = [
                "item_id" => $entry['item_id'],
                "status" => "updated",
                "quantity" => $new_quantity,
                "price" => $new_price / 100,

            ];
        }
        return  [
            "updated_items" => $updatedItems,
            "total_price" => $cart->items()->sum('price') / 100
        ];
    }

    public function delete($id) {
        $cart = $this->cart->all();
        if ($cart) {
            $item = $cart->items()->where('id',$id)->first();
            if ($item) {
                return $item->delete();
            }
            return false;
        }
    }
}

