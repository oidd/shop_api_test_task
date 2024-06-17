<?php

namespace App\Contracts;

interface ResourceServiceContract
{
    public function index(array $filterBy, array $sortBy);
    public function store(array $params);
    public function update($model, array $params);
    public function destroy($model);

    public function getModel();
}
