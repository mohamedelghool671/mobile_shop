<?php

namespace App\Reposities;

use App\Interfaces\CategoryReposityInterface;
use App\Models\Category;

class CategoryReposity implements CategoryReposityInterface
{
    public function all($limit = 100) {
        return Category::paginate($limit);
    }

    public function create($data) {
        return Category::create($data);
    }

    public function update($data,$category) {
       return tap($category,function($category) use ($data) { 
        return $category->update($data);
       });
    }
}
