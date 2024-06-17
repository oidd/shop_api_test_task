<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $hidden = ['created_at', 'updated_at'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
