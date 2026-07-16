<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductAudit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;
    private function createProductWithPrice(float $price = 100.0, string $currency = 'USD', int $stock = 10): Product
    {
        $category = Category::create([
            'name'        => 'Тестовая категория',
            'description' => 'Описание',
        ]);

        $product = Product::create([
            'name'        => 'Тестовый товар',
            'description' => 'Описание товара',
            'category_id' => $category->categoryId,
            'discount_id' => null,
        ]);

        Price::create([
            'product_id' => $product->productId,
            'price'      => $price,
            'currency'   => $currency,
            'is_active'  => true,
        ]);

        ProductAudit::create([
            'product_id' => $product->productId,
            'quantity'   => $stock,
        ]);

        return $product;
    }

    public function test_cart_page_opens(): void {
        $response = $this->get('/cart');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_add_to_cart(): void {
        $user = User::factory()->create();
        $product = $this->createProductWithPrice(price: 150.0);

        $response = $this->actingAs($user)->postJson('/api/cart', [
            'productId' => $product->productId,
            'quantity' => 2,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('total', 300);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->productId,
            'quantity' => 2,
        ]);
    }

    public function test_adding_nonexistent_product_returns_error(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/cart', [
            'productId' => 999999,
            'quantity'  => 1,
        ]);

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_remove_product_from_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->createProductWithPrice();

        $this->actingAs($user)->postJson('/api/cart', [
            'productId' => $product->productId,
            'quantity'  => 1,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/cart/{$product->productId}");

        $response->assertStatus(200);
        $response->assertJsonPath('total', 0);

        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $product->productId,
        ]);
    }

    public function test_authenticated_user_can_clear_cart(): void
    {
        $user = User::factory()->create();
        $productA = $this->createProductWithPrice();
        $productB = $this->createProductWithPrice();

        $this->actingAs($user)->postJson('/api/cart', [
            'productId' => $productA->productId,
            'quantity' => 1
        ]);
        $this->actingAs($user)->postJson('/api/cart', [
            'productId' => $productB->productId,
            'quantity' => 1
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/cart');

        $response->assertStatus(200);
        $response->assertJson(['data' => [], 'total' => 0]);

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_guest_can_add_product_to_cart(): void
    {
        $product = $this->createProductWithPrice(price: 50.0);

        Redis::shouldReceive('get')->andReturn(null);
        Redis::shouldReceive('setex')->andReturnTrue();

        $response = $this->postJson('/api/cart', [
            'productId' => $product->productId,
            'quantity'  => 1,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('total', 50);

        $response->assertCookie('guest_cart_id');
    }
}
