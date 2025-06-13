<?php

namespace App\Services;

use App\Helpers\Paginate;
use App\Helpers\ApiResponse;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\ProductReposityInterface;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function __construct(protected ProductReposityInterface $product){}

    public function all($limit,$name) {
        $products = $this->product->all($limit ?? 50,$name ?? null);
        $data = Paginate::paginate($products, ProductResource::collection($products), 'products');
        if ($data) {
            return ApiResponse::sendResponse("List of products", 200, $data);
        }
        return ApiResponse::sendResponse("No products", 404);
    }

    public function create($data) {
        $data['price'] = $data['price'] * 100;

        $imageNames = [];
        if (isset($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $image) {
               $filename = Storage::disk('public')->putFile('gallery', $image);
                $imageNames[] = $filename;
            }
        }
        $data['image'] = Storage::disk('public')->putFile('products', $data['image']);
        $data['images'] = json_encode($imageNames);
        $product = $this->product->store($data);
        return ApiResponse::sendResponse("Product created successfully", 201, new ProductResource($product));
    }

    public function show($product, $user) {
        if ($user) {
            $visit = $user->products()->where('product_id', $product->id)
                ->where('user_id', $user->id)
                ->first();

            if ($visit) {
                $user->products()->updateExistingPivot($product->id, [
                    'visit_count' => $visit->pivot->visit_count + 1,
                    'last_visit' => now(),
                ]);
            } else {
                $user->products()->attach($product->id, [
                    'visit_count' => 1,
                    'last_visit' => now(),
                ]);
            }
        }
        return ApiResponse::sendResponse("Product retrieved successfully", 200, new ProductResource($product));
    }

    public function update($product, $data) {
        $data['price'] = $data['price'] * 100;
         $imageNames = [];
    if (isset($data['images']) && is_array($data['images'])) {
        foreach ($data['images'] as $image) {
            $filename = Storage::disk('public')->putFile('gallery', $image);
            if ($filename) {
                $imageNames[] = $filename;
            }
        }
        if (count($imageNames) > 0) {
            if ($product->images) {
                $oldImages = json_decode($product->images, true);
                if (is_array($oldImages)) {
                    foreach ($oldImages as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            $data['images'] = json_encode($imageNames);
        }
    }
        $product = $this->product->update($product, $data);
        return ApiResponse::sendResponse("Product updated successfully", 200, new ProductResource($product));
    }

    public function delete($product) {
    if ($product->images) {
                $oldImages = json_decode($product->images, true);
                if (is_array($oldImages)) {
                    foreach ($oldImages as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            $this->product->delete($product);
            return ApiResponse::sendResponse("Product deleted successfully", 200);
        }

    public function latest() {
        $latest = $this->product->latest();
        if ($latest) {
            $data = Paginate::paginate($latest, ProductResource::collection($latest), "products");
            if ($data) {
                return ApiResponse::sendResponse("Latest products retrieved successfully", 200, $data);
            }
        }
        return ApiResponse::sendResponse("No new products", 404);
    }

    public function userVisite($request) {
        $products = $this->product->userVisite($request);
        if ($products) {
            $productsWithVisitCount = $products->map(function ($product) {
                $product->total_visits = $product->pivot->visit_count;
                $product->last_visit = $product->pivot->last_visit;
                return $product;
            });
            return ApiResponse::sendResponse("User visited products retrieved successfully", 200, ProductResource::collection($productsWithVisitCount));
        }
        return ApiResponse::sendResponse("No visits", 404);
    }

    public function allVisite() {
        $products = $this->product->allVisite();
        if ($products) {
            return ApiResponse::sendResponse("Most visited products retrieved successfully", 200, ProductResource::collection($products));
        }
        return ApiResponse::sendResponse("No visits", 404);
    }

    public function search($query) {
        $product =  $this->product->search($query);
        if ($product) {
            return ApiResponse::sendResponse("Product retrieved successfully", 200,  ProductResource::collection($product));
        }
          return ApiResponse::sendResponse("product not found",404);
    }
}