<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DestroyRequest;
use App\Http\Requests\Admin\IndexRequest;
use App\Http\Requests\Admin\ShowRequest;
use App\Http\Requests\Admin\UpdateRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use App\Service\Auth\AuthService;
use App\Service\ResourceServices\AdminService;

class AdminController extends Controller
{
    public function __construct(
        private AdminService $service,
        private AuthService $authService
    )
    {}

    public function index(IndexRequest $request)
    {
        return response()->json(
            $this->retrieveAsResourceAndPaginate(
                AdminResource::class,
                $this->service->index(
                    $request->safe()->except(['sort']) ?? array(),
                    $request->input(['sort']) ?? array()),
                $request
            )
        );
    }

    public function show(ShowRequest $request, Admin $admin)
    {
        return response()->json(
            new AdminResource($admin)
        );
    }

    public function store(RegisterRequest $request)
    {
        return response()->json(
            new AdminResource($this->authService->register($request->validated()))
        );
    }

    public function update(UpdateRequest $request, Admin $admin)
    {
        return response()->json(
            new AdminResource($this->service->update($admin, $request->validated()))
        );
    }

    public function destroy(DestroyRequest $request, Admin $admin)
    {
        return response()->json(
            new AdminResource($this->service->destroy($admin))
        );
    }
}
