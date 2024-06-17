<?php

namespace App\Traits;

trait ResourceDefaultMethods
{
    public function store(array $params)
    {
        return $this->getModel()::create($params)->refresh();
        // refresh, so when db set default values, this object have it
    }

    public function update($model, array $params)
    {
        $model->update($params);

        return tap($model)->save();
    }

    public function destroy($model)
    {
        $model->delete();

        return tap($model)->save();
    }
}
