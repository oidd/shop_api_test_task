<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\DestroyRequest;
use App\Http\Requests\Product\IndexRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Service\ResourceServices\ProductService;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $service,
    )
    {}

    public function index(IndexRequest $request)
    {
        return response()->json(
            $this->retrieveAsResourceAndPaginate(
                ProductResource::class,
                $this->service->index(
                    $request->safe()->except(['sort']) ?? array(),
                    $request->input(['sort']) ?? array()),
                $request
            )
        );
    }

    public function show(Product $product)
    {
        return response()->json(
            new ProductResource($product)
        );
    }

    public function store(StoreRequest $request)
    {
        return response()->json(
            new ProductResource($this->service->store($request->validated()))
        );
    }

    public function update(UpdateRequest $request, Product $product)
    {
        return response()->json(
            new ProductResource($this->service->update($product, $request->validated()))
        );
    }

    public function destroy(Product $product)
    {
        return response()->json(
            new ProductResource($this->service->destroy($product))
        );
    }
}
