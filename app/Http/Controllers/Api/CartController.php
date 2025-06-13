<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\CartItemRequest;
use App\Http\Requests\Api\CartRequest;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(protected CartService $cart){}
    public function index(Request $request)
    {
        return $this->cart->all($request);
    }

    public function store(CartItemRequest $request)
    {
        return $this->cart->create($request);
    }

    public function update(CartRequest $request)
    {
        return $this->cart->update($request);
    }

    public function destroy(Request $request,string $id)
    {
       return $this->cart->delete($request,$id);
    }
}
