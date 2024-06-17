<?php

namespace App\Models;

use App\Traits\HandlesMoney;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    use HandlesMoney;

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'stock',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['price'];

    public function price(): Attribute
    {
        return $this->handlingMoneyAttributes();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
