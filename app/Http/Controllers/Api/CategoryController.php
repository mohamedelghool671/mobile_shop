<?php

namespace App\Http\Controllers\Api;


use App\Models\Category;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Api\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;


class CategoryController extends \Illuminate\Routing\Controller
{

    public function __construct(protected CategoryService $category)
    {
        $this->middleware("auth:sanctum");
        $this->middleware("admin")->only(["store","destroy","update"]);
    }

    public function index(Request $request)
    {
        $data = $this->category->all($request->limit ?? 10);
        return $data ? ApiResponse::sendResponse("List of categories", 200, $data) :
         ApiResponse::sendResponse("No categories found", 404);
    }

    public function store(CategoryRequest $request)
    {
       $data = $this->category->create(fluent($request->validated()));
       return $data ? ApiResponse::sendResponse("Category created successfully", 201, new CategoryResource($data)) :
        ApiResponse::sendResponse("Category already exists", 422);
    }

    public function show(Category $category)
    {
        return ApiResponse::sendResponse("category retrived success",200,new CategoryResource($category));
    }

    public function update(CategoryRequest $request,Category $category)
    {
        $data = $this->category->update(fluent($request->validated()),$category);
        return $data ? ApiResponse::sendResponse("Category updated successfully", 200, new CategoryResource($data)):
        ApiResponse::sendResponse("Category already exists", 422);
    }

    public function destroy(Category $category)
    {
         $this->category->delete($category) ;
         return ApiResponse::sendResponse("Category deleted successfully", 200);
    }
}
