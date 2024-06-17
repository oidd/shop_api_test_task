<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Controller
{
    // this is a bit frustrating, I know. maybe will refactor later
    protected function retrieveAsResourceAndPaginate(
        string  $resource,
        Builder $retrievedData,
        Request $request,
        string  $perPage = 'perPage'
    )
    {
        return call_user_func([$resource, 'collection'],
            $retrievedData
                ->simplePaginate($request->input($perPage) ?? config('api.paginate.perPage'))
                ->withQueryString()
        )->response()->getData(true);
    }
}
