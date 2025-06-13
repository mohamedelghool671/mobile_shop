<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\ProductRequest;
use App\Services\ProductService;

class ProductController extends \Illuminate\Routing\Controller
{

    public function __construct(protected ProductService $product)
    {
        $this->middleware('admin')->except(["index","show",'latest']);
        $this->middleware('auth:sanctum')->except(["index","show",'latest']);
    }

    public function index(Request $request)
    {
        return $this->product->all($request->limit,$request->category_id);
    }

    public function store(ProductRequest $request)
    {
        return $this->product->create($request->validated());
    }

    public function show(Product $product, Request $request)
    {
        Auth::shouldUse('sanctum');
        $user = $request->user();
        return $this->product->show($product,$user);
    }

    public function update(ProductRequest $request, Product $product)
    {
        return $this->product->update($product,$request->validated());
    }

    public function destroy(Product $product)
    {
       return $this->product->delete($product);
    }

    public function latest() {
        return $this->product->latest();
    }

    public function userVisitedProducts(Request $request)
    {
        return $this->product->userVisite($request);
    }

    public function mostVisitedProducts()
    {
       return $this->product->allVisite();
    }

    public function search(Request $request) {
        return $this->product->search($request->query('param'));
    }
}
