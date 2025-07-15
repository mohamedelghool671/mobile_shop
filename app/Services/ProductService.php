<?php

namespace App\Services;

use App\Helpers\Paginate;
use App\Helpers\SendNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\ProductReposityInterface;

class ProductService
{
    public function __construct(protected ProductReposityInterface $product){}

    public function all($limit,$category_id) {
        $products = $this->product->all($limit ?? 50,$category_id ?? null);
        return Paginate::paginate($products, ProductResource::collection($products), 'products');
    }

    public function create($data) {
        $data['price'] = $data->price * 100;
        $imageNames = [];
        if ($data->images && is_array($data->images)) {
            foreach ($data->images as $image) {
               $filename = Storage::disk('public')->putFile('gallery', $image);
                $imageNames[] = $filename;
            }
        }
        $data['image'] = Storage::disk('public')->putFile('products', $data->image);
        $data['images'] = json_encode($imageNames);
        $product = $this->product->store($data->toArray());
        SendNotification::sendAll("New Product : $product->name","About Product : $product->description",('storage/'.$product->image));
        return $product;
    }

public function show($product) {
    $user = auth()->user();
    if ($user) {
        $visit = $user->products()
            ->where('product_id', $product->id)
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
    $product->load(['comment.user', 'users']);
    $product->loadCount([
        'comment as rating' => function ($q) {
            $q->select(DB::raw('avg(rating)'));
        }
    ]);

    return $product;
}

    public function update($product, $data) {
        $data['price'] = $data->price * 100;
         $imageNames = [];
    if ($data->images && is_array($data->images)) {
        foreach ($data->images as $image) {
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
       return $product = $this->product->update($product, $data->toArray());
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
           return $this->product->delete($product);
        }

    public function latest() {
        $latest = $this->product->latest();
        return $data = Paginate::paginate($latest, ProductResource::collection($latest), "products");
    }

    public function userVisite($request) {
        $products = $this->product->userVisite($request);
        if ($products) {
             return $products->map(function ($product) {
                $product->total_visits = $product->pivot->visit_count;
                $product->last_visit = $product->pivot->last_visit;
                return $product;
            });
        }
    }

    public function allVisite() {
        return $this->product->allVisite();
    }

    public function search($query) {
        $product =  $this->product->search($query);
        if ($product->isEmpty()) {
            return false;
        }
        return $product;
    }
}