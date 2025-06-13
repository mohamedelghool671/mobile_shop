<?php

namespace App\Reposities;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Interfaces\ProductReposityInterface;
use SocialiteProviders\Facebook\FacebookExtendSocialite;
class ProductReposity implements ProductReposityInterface
{
public function all($limit,$category_id) {
    $key = 'products_' . ($category_id ?? 'all') . '_' . ($limit ?? 'default');
    $products = Cache::remember($key, 60, function () use ($limit, $category_id) {
        $query = Product::with(['category', 'comment', 'users']);
        if ($category_id) {
            $query->where('category_id', $category_id);
        }
        return $query->paginate($limit);
    });

    return $products;
}

    public function store($request) {
        return Product::create($request);
    }

    public function update($product,$request) {
        $product->update($request);
        return $product;
    }

    public function delete($product) {
        $product->delete();
    }

    public function latest()
    {
        $latest = Cache::remember('latest',60,function() {
            return Product::with(['comment','category'])->latest("id")->paginate(10);
        });
        return $latest;
    }

    public function userVisite($request)
    {
        $user = $request->user();
        $products = $user->products()
            ->with('category', 'comment')
            ->orderByDesc('product_views.visit_count')
            ->take(20)
            ->get();
        return $products;

    }

    public function allVisite()
    {
        $products = Product::query()
        ->join('product_views', 'products.id', '=', 'product_views.product_id')
        ->groupBy('products.id')
        ->orderByDesc(DB::raw('SUM(product_views.visit_count)'))
        ->select('products.*', DB::raw('SUM(product_views.visit_count) as total_visits'))
        ->with('category','comment')
        ->take(20)
        ->get();
        return $products;
    }

    public function search($query) {
        $product = Product::query();
        if (is_numeric($query)) {
            $product->where('price', $query * 100);
        } else {
            $product->where('name', 'LIKE', "%$query%");
        }
        return $product->get();
    }
}
