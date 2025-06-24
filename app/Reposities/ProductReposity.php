<?php

namespace App\Reposities;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Interfaces\ProductReposityInterface;

class ProductReposity implements ProductReposityInterface
{
    public function all($limit = 20, $category_id)
    {
        $key = 'products_' . ($category_id ?? 'all') . '_' . ($limit ?? 'default');
        return Cache::remember($key, 60, function () use ($limit, $category_id) {
            $query = Product::with(['category', 'comment.user'])
                ->withCount(['comment as rating' => function ($q) {
                    $q->select(DB::raw("avg(rating)"));
                }])
                ->addSelect([
                    'is_favourite' => DB::table('favourites')
                        ->selectRaw('1')
                        ->whereColumn('favourites.product_id', 'products.id')
                        ->where('favourites.user_id', auth()->id())
                        ->limit(1)
                ]);

            if ($category_id) {
                $query->where('category_id', $category_id);
            }

            return $query->paginate($limit);
        });
    }

    public function store($data)
    {
        return Product::create($data);
    }

    public function update($product, $data)
    {
        return tap($product, function ($product) use ($data) {
            return $product->update($data);
        });
    }

    public function delete($product)
    {
        return $product->delete();
    }

    public function latest()
    {
        return Cache::remember('latest', 60, function () {
            return Product::with(['comment.user', 'category'])
                ->withCount(['comment as rating' => function ($q) {
                    $q->select(DB::raw("avg(rating)"));
                }])
                ->addSelect([
                    'is_favourite' => DB::table('favourites')
                        ->selectRaw('1')
                        ->whereColumn('favourites.product_id', 'products.id')
                        ->where('favourites.user_id', auth()->id())
                        ->limit(1)
                ])
                ->latest("id")
                ->paginate(10);
        });
    }

    public function userVisite()
    {
        $user = auth()->user();
        return $user->products()
            ->with(['category', 'comment.user'])
            ->withCount(['comment as rating' => function ($q) {
                $q->select(DB::raw("avg(rating)"));
            }])
            ->addSelect([
                'is_favourite' => DB::table('favourites')
                    ->selectRaw('1')
                    ->whereColumn('favourites.product_id', 'products.id')
                    ->where('favourites.user_id', $user->id)
                    ->limit(1)
            ])
            ->orderByDesc('product_views.visit_count')
            ->take(20)
            ->get();
    }

    public function allVisite()
    {
        return Product::query()
            ->join('product_views', 'products.id', '=', 'product_views.product_id')
            ->groupBy('products.id')
            ->orderByDesc(DB::raw('SUM(product_views.visit_count)'))
            ->select(
                'products.*',
                DB::raw('SUM(product_views.visit_count) as total_visits'),
                DB::raw('MAX(product_views.last_visit) as last_visit')
            )
            ->with(['category', 'comment.user'])
            ->withCount(['comment as rating' => function ($q) {
                $q->select(DB::raw("avg(rating)"));
            }])
            ->addSelect([
                'is_favourite' => DB::table('favourites')
                    ->selectRaw('1')
                    ->whereColumn('favourites.product_id', 'products.id')
                    ->where('favourites.user_id', auth()->id())
                    ->limit(1)
            ])
            ->take(20)
            ->get();
    }

    public function search($query)
    {
        return Product::with(['category', 'comment.user'])
            ->withCount(['comment as rating' => function ($q) {
                $q->select(DB::raw("avg(rating)"));
            }])
            ->addSelect([
                'is_favourite' => DB::table('favourites')
                    ->selectRaw('1')
                    ->whereColumn('favourites.product_id', 'products.id')
                    ->where('favourites.user_id', auth()->id())
                    ->limit(1)
            ])
            ->where('name', 'LIKE', "%$query%")
            ->get();
    }
}
