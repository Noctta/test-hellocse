<?php

namespace Database\Factories;


use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ProductStatus;


class ProductFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->word(),
            'price'       => fake()->randomFloat(nbMaxDecimals: 2, min: 0.99, max: 999.99),
            'image'       => fake()->imageUrl(640, 480, 'products'),
            'status'      => fake()->randomElement(ProductStatus::cases()),
            'category_id' => Category::factory(),
        ];
    }

    public function online(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ProductStatus::ONLINE,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ProductStatus::DRAFT,
        ]);
    }
}
