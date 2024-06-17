<?php

namespace App\Models;

use App\Events\OrderSaved;
use App\Events\OrderUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'status'];

    protected $hidden = ['updated_at'];

    protected $appends = ['amount', 'created_at'];

    protected $dispatchesEvents = [
        'saved'     => OrderSaved::class,
        'updated'   => OrderUpdated::class
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('count');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function amount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $sum = 0;
                // getting original values from db without mutators applied
                foreach ($this->products as $product)
                    $sum += $product->getAttributes()['price'] * $product->pivot->count;
                return $sum / 100;
            }
        );
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::createFromTimeString($value)->format('Y.m.d')
        );
    }
}
