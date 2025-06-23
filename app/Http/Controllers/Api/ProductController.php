<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Resources\ProductResource;
use App\Http\Requests\Api\ProductRequest;

class ProductController extends \Illuminate\Routing\Controller
{

    public function __construct(protected ProductService $product)
    {
        $this->middleware('admin')->except(["index","show",'latest','search']);
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $data = $this->product->all($request->limit,$request->category_id);
        return $data ? ApiResponse::sendResponse("List of products", 200, $data) :
         ApiResponse::sendResponse("No products", 404);
    }

    public function store(ProductRequest $request)
    {
        $data = $this->product->create(fluent($request->validated()));
         return $data ? ApiResponse::sendResponse("Product created successfully", 201, new ProductResource($data)) :
         ApiResponse::sendResponse("failed while create product",422);
    }

    public function show(Product $product, Request $request)
    {
         $data = $this->product->show($product);
         return ApiResponse::sendResponse("Product retrieved successfully", 200, new ProductResource($data));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $this->product->update($product,fluent($request->validated()));
        return ApiResponse::sendResponse("Product updated successfully", 200, new ProductResource($data));
    }

    public function destroy(Product $product)
    {
        $this->product->delete($product);
         return ApiResponse::sendResponse("Product deleted successfully", 200);
    }

    public function latest() {
        $data = $this->product->latest();
        return $data ? ApiResponse::sendResponse("Latest products retrieved successfully", 200, $data) :
        ApiResponse::sendResponse("No new products", 404);
    }

    public function userVisitedProducts(Request $request)
    {
        $data = $this->product->userVisite($request);
        return $data ? ApiResponse::sendResponse("User visited products retrieved successfully", 200, ProductResource::collection($data)):
        ApiResponse::sendResponse("No visits", 404);
    }

    public function mostVisitedProducts()
    {
       $data = $this->product->allVisite();
       return $data ? ApiResponse::sendResponse("Most visited products retrieved successfully", 200, ProductResource::collection($data)):
     ApiResponse::sendResponse("No visits", 404);
    }

    public function search(Request $request) {
        $data = $this->product->search($request->query('param'));
        return $data ? ApiResponse::sendResponse("Product retrieved successfully", 200,  ProductResource::collection($data)) :
         ApiResponse::sendResponse("product not found",404);
    }
}
