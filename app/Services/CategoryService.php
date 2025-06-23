<?php

namespace App\Services;

use Exception;
use App\Helpers\Paginate;
use App\Http\Resources\CategoryResource;
use App\Interfaces\CategoryReposityInterface;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function __construct(protected CategoryReposityInterface $category)
    {
    }

    public function all($limit) {
        $categories = $this->category->all($limit);
        return Paginate::paginate($categories, CategoryResource::collection($categories), 'categories');
    }

    public function create($data) {
        try {
            if ($data->category_image) {
                $data['category_image'] = Storage::putFile('categories',$data->category_image);
            }
            return $this->category->create($data->toArray());
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($data, $category) {
        try {
             if (isset($data->category_image)) {
                Storage::delete($category->category_image);
                $data['category_image'] = Storage::putFile('categories',$data->category_image);
            }
            return $this->category->update($data->toArray(), $category);
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($category) {
        Storage::delete($category->category_image);
        return $category->delete();
    }
}