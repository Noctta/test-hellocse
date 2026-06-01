<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\CategoryStatus;


class CategoryFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name'   => fake()->unique()->word(),
            'image'  => fake()->imageUrl(640, 480, 'categories'),
            'status' => fake()->randomElement(CategoryStatus::cases()),
        ];
    }


    public function online(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => CategoryStatus::ONLINE,
        ]);
    }
}
