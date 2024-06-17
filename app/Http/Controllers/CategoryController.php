<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\DestroyRequest;
use App\Http\Requests\Category\IndexRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Service\ResourceServices\CategoryService;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $service
    )
    {}

    public function index(IndexRequest $request)
    {
        return response()->json(
            $this->retrieveAsResourceAndPaginate(
                CategoryResource::class,
                $this->service->index(
                    $request->safe()->except(['sort']) ?? array(),
                    $request->input(['sort']) ?? array()),
                $request
            )
        );
    }

    public function show(Category $category)
    {
        return response()->json(
            new CategoryResource($category)
        );
    }

    public function update(UpdateRequest $request, Category $category)
    {
        return response()->json(
            new CategoryResource($this->service->update($category, $request->validated()))
        );
    }

    public function destroy(DestroyRequest $request, Category $category)
    {
        return response()->json(
            new CategoryResource($this->service->destroy($category))
        );
    }
}
