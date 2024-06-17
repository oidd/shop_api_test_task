<?php

namespace App\Service\ResourceServices;

use App\Models\Product;
use App\Utils\Filtering\Filter;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    public function index($filterBy, $sortBy): Builder
    {
        $filter = new Filter([
            'name' =>
                fn ($data, $req) => $data->whereRaw("name ilike '%{$req}%'"),
            'category_id' =>
                fn ($data, $req) => $data->whereIn('category_id', $req),
            'price_range' =>
                fn ($data, $req) => $data->whereBetween('price', array_map(fn ($v) => (float) $v * 100, explode('-', $req))),
            'stock_range' =>
                fn ($data, $req) => $data->whereBetween('stock', explode('-', $req)),
            'in_stock' =>
                fn ($data, $req) => $data->where('stock', $req === true ? '>' : '=', '0'),
        ]);

        return $filter->apply(Product::query(),
            filterRequirements: $filterBy,
            sortRequirements: $sortBy,
        );
    }

    public function store(array $params): Product
    {
        return Product::create($params)->refresh();
        //refresh, so when db set default value to 'stock' attribute, this object have it
    }

    public function update(Product $product, array $params)
    {
        $product->update($params);

        return tap($product)->save($params)->refresh();
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return tap($product)->save();
    }
}
