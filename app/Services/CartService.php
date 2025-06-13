<?php

namespace App\Services;

use App\Models\Product;
use App\Models\CartItems;
use App\Helpers\ApiResponse;
use App\Interfaces\CartReposityInterface;

class CartService
{

    public function __construct(protected CartReposityInterface $cart){ }

    public function all($request) {
        $cart = $this->cart->all($request);
        if ($cart) {
            $items = $cart->items()->with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'image' => asset('storage/' . $item->product->image),
                    'price' => $item->price / 100,
                    'quantity' => $item->quantity,
                ];
            });

            if ($items) {
                return ApiResponse::sendResponse("cart retrived success",200,[
                    "cart_id" => $cart->id,
                    "items" => $items,
                    "total_price" => $items->sum('price')
                ]);
            }
            return ApiResponse::sendResponse("cart is empty",422);
        }
        return ApiResponse::sendResponse("cart not found",422);
        }

    public function create($request) {
        $cart = $this->cart->create($request);
        $item_data = $request->validated();
        $product = Product::find($item_data['product_id']);
        if ($item_data['quantity'] < 1) {
            $item_data['quantity'] = 1;
        }

        $existingItem = $cart->items()
        ->where('product_id', $item_data['product_id'])
        ->first();

        if ($existingItem) {
            $existingItem->quantity += $item_data['quantity'];
            $existingItem->price = $product->price * $existingItem->quantity;
            $existingItem->save();
        } else {
            $item_data['cart_id'] = $cart->id;
            $item_data['price'] = $product->price * $item_data['quantity'];
            CartItems::create($item_data);
        }
        return ApiResponse::sendResponse("Cart added success successfully", 200,[
            "cart_id" => $cart->id
        ]);
    }

    public function update($request) {
        $cart = $this->cart->all($request);
        if (!$cart) {
            return ApiResponse::sendResponse("cart not found", 422);
        }
        $data = $request->validated();
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
        return ApiResponse::sendResponse("cart items updated", 200, [
            "updated_items" => $updatedItems,
            "total_price" => $cart->items()->sum('price') / 100
        ]);
    }

    public function delete($request,$id) {
        $cart = $this->cart->all($request,$id);
        if ($cart) {
            $item = $cart->items()->where('id',$id)->first();
            if ($item) {
                $item->delete();
                return ApiResponse::sendResponse("item delete from cart",200);
            }
            return ApiResponse::sendResponse("item not found in cart",422);
        }
        return ApiResponse::sendResponse("user haven't any cart",422);
    }
}

