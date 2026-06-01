<?php

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->category = Category::factory()->create();
});

// -------------------- INDEX ------------------------------

it('can list products', function () {
    Product::factory()->count(5)->for($this->category)->create();

    $this->getJson('/api/products')
        ->assertOk()
        ->assertJsonCount(5, 'data');
});

it('can filter products by category', function () {
    $otherCategory = Category::factory()->create();

    Product::factory()->count(3)->for($this->category)->create();
    Product::factory()->count(2)->for($otherCategory)->create();

    $this->getJson("/api/products?category_id={$this->category->id}")
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('includes category in product list', function () {
    Product::factory()->for($this->category)->create();

    $this->getJson('/api/products')
        ->assertOk()
        ->assertJsonPath('data.0.category.id', $this->category->id);
});

// -------------------- SHOW ------------------------------

it('can show a product', function () {
    $product = Product::factory()->for($this->category)->create();

    $this->getJson("/api/products/{$product->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $product->id)
        ->assertJsonPath('data.category.id', $this->category->id);
});

it('returns 404 when product does not exist', function () {
    $this->getJson('/api/products/999')
        ->assertNotFound();
});

// -------------------- STORE ------------------------------

it('can create a product', function () {
    $payload = [
        'name'        => 'MacBook Pro',
        'price'       => 1999.99,
        'image'       => UploadedFile::fake()->image('product.jpg'),
        'status'      => ProductStatus::ONLINE,
        'category_id' => $this->category->id,
    ];

    $this->postJson('/api/products', $payload)
        ->assertCreated()
        ->assertJsonPath('data.name', 'MacBook Pro')
        ->assertJsonPath('data.price', '1999.99');

    expect(Product::count())->toBe(1);
});

it('fails to create a product without required fields', function () {
    $this->postJson('/api/products', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'price', 'image', 'category_id']);
});

it('fails to create a product with nonexistent category', function () {
    $payload = [
        'name'        => 'Test',
        'price'       => 10,
        'image'       => UploadedFile::fake()->image('product.jpg'),
        'category_id' => 9999,
    ];

    $this->postJson('/api/products', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['category_id']);
});

it('fails to create a product with negative price', function () {
    $payload = [
        'name'        => 'Test',
        'price'       => -5,
        'image'       => UploadedFile::fake()->image('product.jpg'),
        'category_id' => $this->category->id,
    ];

    $this->postJson('/api/products', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['price']);
});

// -------------------- UPDATE ------------------------------

it('can update a product', function () {
    $product = Product::factory()->for($this->category)->create();

    $this->putJson("/api/products/{$product->id}", [
        'name'  => 'Nom mis à jour',
        'price' => 49.99,
    ])
        ->assertOk()
        ->assertJsonPath('data.name', 'Nom mis à jour')
        ->assertJsonPath('data.price', '49.99');
});

it('can update a product category', function () {
    $product = Product::factory()->for($this->category)->create();
    $newCategory = Category::factory()->create();

    $this->putJson("/api/products/{$product->id}", [
        'category_id' => $newCategory->id,
    ])
        ->assertOk()
        ->assertJsonPath('data.category.id', $newCategory->id);
});

// -------------------- DESTROY ------------------------------

it('can delete a product', function () {
    $product = Product::factory()->for($this->category)->create();

    $this->deleteJson("/api/products/{$product->id}")
        ->assertNoContent();

    expect(Product::count())->toBe(0);
});