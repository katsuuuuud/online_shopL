<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductAudit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogPageTest extends TestCase
{
    use RefreshDatabase;

    private function createProductWithPrice(float $price = 100.0, string $currency = 'USD'): Product
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

        return $product;
    }

    public function test_user_cannot_add_more_than_available_stock_to_cart(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProductWithPrice();

        ProductAudit::create([
            'product_id' => $product->productId,
            'quantity'   => 2,
        ]);

        $response = $this->actingAs($user)->postJson('/api/cart', [
            'productId' => $product->productId,
            'quantity'  => 5,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'Недостаточно товара на складе']);

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_user_cannot_buy_product_that_is_out_of_stock(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProductWithPrice();

        ProductAudit::create([
            'product_id' => $product->productId,
            'quantity'   => 0,
        ]);

        $cart = Cart::create(['user_id' => $user->userId]);

        CartItem::create([
            'cart_id'    => $cart->cartId,
            'product_id' => $product->productId,
            'quantity'   => 1,
            'price'      => 100.0,
            'currency'   => 'USD',
        ]);

        $response = $this->actingAs($user)->postJson('/api/orders');

        $response->assertStatus(422);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertDatabaseHas('product_audit', [
            'product_id' => $product->productId,
            'quantity'   => 0,
        ]);
    }
}
