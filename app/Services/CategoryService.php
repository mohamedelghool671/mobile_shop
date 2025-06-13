<?php

namespace App\Services;

use Exception;
use App\Helpers\Paginate;
use App\Helpers\ApiResponse;
use App\Reposities\CategoryReposity;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function __construct(protected CategoryReposity $category)
    {
    }

    public function all($limit) {
        $categories = $this->category->all($limit);
        $data = Paginate::paginate($categories, CategoryResource::collection($categories), 'categories');
        if ($data) {
            return ApiResponse::sendResponse("List of categories", 200, $data);
        }
        return ApiResponse::sendResponse("No categories found", 404);
    }

    public function create($request) {
        try {
            if (isset($request['category_image'])) {
                $request['category_image'] = Storage::putFile('categories',$request['category_image']);
            }
            $category = $this->category->create($request);
            return ApiResponse::sendResponse("Category created successfully", 201, new CategoryResource($category));
        } catch (Exception $e) {
            return ApiResponse::sendResponse("Category already exists", 422, []);
        }
    }

    public function update($request, $category) {
        try {
             if (isset($request['category_image'])) {
                Storage::delete($category->category_image);
                $request['category_image'] = Storage::putFile('categories',$request['category_image']);
            }
            $newCategory = $this->category->update($request, $category);
            return ApiResponse::sendResponse("Category updated successfully", 200, new CategoryResource($newCategory));
        } catch (Exception $e) {
            return ApiResponse::sendResponse("Category already exists", 422, []);
        }
    }

    public function delete($category) {
        Storage::delete($category->category_image);
        $this->category->delete($category);
        return ApiResponse::sendResponse("Category deleted successfully", 200);
    }
}