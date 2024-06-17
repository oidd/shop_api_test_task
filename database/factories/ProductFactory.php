<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->domainWord(),
            'category_id' => rand(1, Category::count()),
            'price' => $this->faker->randomNumber(3),
            'stock' => $this->faker->randomNumber(2),
        ];
    }
}
