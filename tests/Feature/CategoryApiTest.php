<?php

use App\Enums\CategoryStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


// -------------------- INDEX ------------------------------

it('can list categories', function () {
    Category::factory()->count(3)->create();

    $this->getJson('/api/categories')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('includes online products count in category list', function () {
    $category = Category::factory()->create();

    Product::factory()->count(2)->online()->for($category)->create();
    Product::factory()->draft()->for($category)->create();

    $this->getJson('/api/categories')
        ->assertOk()
        ->assertJsonPath('data.0.online_products_count', 2);
});

// -------------------- SHOW ------------------------------

it('can show a category', function () {
    $category = Category::factory()->create();

    $this->getJson("/api/categories/{$category->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $category->id)
        ->assertJsonPath('data.name', $category->name);
});

it('returns 404 when category does not exist', function () {
    $this->getJson('/api/categories/999')
        ->assertNotFound();
});

// -------------------- STORE ------------------------------

it('can create a category', function () {
    $payload = [
        'name'   => 'Électronique',
        'image'  => "https://www.hellocse.fr/images/logo/hellocse.svg?v=2",
        'status' => CategoryStatus::ONLINE,
    ];

    $this->postJson('/api/categories', $payload)
        ->assertCreated()
        ->assertJsonPath('data.name', 'Électronique')
        ->assertJsonPath('data.status', 'online');

    expect(Category::count())->toBe(1);
});

it('fails to create a category without name', function () {
    $payload = [
        'image' => "https://www.hellocse.fr/images/logo/hellocse.svg?v=2",
    ];

    $this->postJson('/api/categories', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

it('fails to create a category with invalid status', function () {
    $payload = [
        'name'   => 'Test',
        'image'  => "https://www.hellocse.fr/images/logo/hellocse.svg?v=2",
        'status' => 'invalide',
    ];

    $this->postJson('/api/categories', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

// -------------------- UPDATE ------------------------------

it('can update a category', function () {
    $category = Category::factory()->create(['name' => 'Ancien nom']);

    $this->putJson("/api/categories/{$category->id}", [
        'name' => 'Nouveau nom',
    ])
        ->assertOk()
        ->assertJsonPath('data.name', 'Nouveau nom');

    expect($category->refresh()->name)->toBe('Nouveau nom');
});

it('can update a category image', function () {
    $category = Category::factory()->create();
    $oldImage = $category->image;

    $this->putJson("/api/categories/{$category->id}", [
        'image' => "https://www.hellocse.fr/images/logo/hellocse.svg?v=2",
    ])->assertOk();

    $category->refresh();

    expect($category->image)->not->toBe($oldImage);
});

// -------------------- DESTROY ------------------------------

it('can delete a category', function () {
    $category = Category::factory()->create();

    $this->deleteJson("/api/categories/{$category->id}")
        ->assertNoContent();

    expect(Category::count())->toBe(0);
});