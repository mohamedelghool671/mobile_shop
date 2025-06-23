<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CartRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\CartItemRequest;

class CartController extends Controller
{
    public function __construct(protected CartService $cart){}
    public function index(Request $request)
    {
        $data = $this->cart->all($request);
        return $data ? ApiResponse::sendResponse("cart retrived success",200,$data) :
        ApiResponse::sendResponse("cart is empty ",422);
    }

    public function store(CartItemRequest $request)
    {
        $data = $this->cart->create(fluent($request->validated()));
        return $data ? ApiResponse::sendResponse("Cart added success successfully", 200,$data) :
        ApiResponse::sendResponse("failed wheile adding",422);
    }

    public function update(CartRequest $request)
    {
        $data = $this->cart->update(fluent($request->validated()));
        return $data ? ApiResponse::sendResponse("cart items updated", 200,$data):
        ApiResponse::sendResponse("cart not found", 422);
    }

    public function destroy(Request $request,string $id)
    {
       $data = $this->cart->delete($id);
       return $data ? ApiResponse::sendResponse("item delete from cart",200) :
       ApiResponse::sendResponse("item not found in cart",422);
    }
}
