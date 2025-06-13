<?php

namespace App\Reposities;

use App\Models\Category;

class CategoryReposity
{
    public function all($limit = 100) {
        return Category::paginate($limit);
    }

    public function create($request) {
        return Category::create($request);
    }

    public function update($request,$category) {
        $category->update($request);
        return $category;
    }

    public function delete($category) {
        return $category->delete();
    }
}
