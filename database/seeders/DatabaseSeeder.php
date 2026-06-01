<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    public function run(): void
    {
        // Créer 5 catégories en ligne, chacune avec 3 à 6 produits
        Category::factory()
            ->count(5)
            ->online()
            ->create()
            ->each(function (Category $category): void {
                Product::factory()
                    ->count(fake()->numberBetween(3, 6))
                    ->for($category)
                    ->create();
            });

            
        // Créer 2 catégories avec d'autres statuts
        Category::factory()
            ->count(2)
            ->create()
            ->each(function (Category $category): void {
                Product::factory()
                    ->count(fake()->numberBetween(1, 3))
                    ->for($category)
                    ->create();
            });
    }
}
