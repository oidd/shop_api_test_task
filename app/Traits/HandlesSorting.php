<?php

namespace App\Traits;

trait HandlesSorting
{
    private function getSortAttributes(array $attributes): string
    {
        $res = implode(',', array_merge($attributes, array_map(fn ($value) => '-' . $value, $attributes)));
        return 'in:' . $res;
    }

    private function wrapAttributesIntoArray(array $attributes)
    {
        $attributes = array_merge($attributes, ['sort']);

        foreach ($attributes as $v) {
            if ($this->has($v) && !is_array($this->input($v)))
                $this->merge([$v => [$this->input($v)]]);
        }
    }
}
