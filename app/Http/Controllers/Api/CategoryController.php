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
        $this->middleware("auth:sanctum")->except(['index','show']);
        $this->middleware("admin")->only(["store","destroy","update"]);
    }

    public function index(Request $request)
    {
            return $this->category->all($request->limit ?? 10);
    }

    public function store(CategoryRequest $request)
    {
       return $this->category->create($request->validated());
    }

    public function show(Category $category)
    {
        return ApiResponse::sendResponse("category retrived success",200,new CategoryResource($category));
    }

    public function update(CategoryRequest $request,Category $category)
    {
        return $this->category->update($request->validated(),$category);
    }

    public function destroy(Category $category)
    {
        return $this->category->delete($category);
    }
}
