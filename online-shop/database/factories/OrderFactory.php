<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductAudit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private function prepareUserWithCartItem(int $stock, int $quantity, float $price = 100.0): array
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();

        ProductAudit::create([
            'product_id' => $product->productId,
            'quantity'   => $stock,
        ]);

        $cart = Cart::create(['user_id' => $user->userId]);

        CartItem::create([
            'cart_id'    => $cart->cartId,
            'product_id' => $product->productId,
            'quantity'   => $quantity,
            'price'      => $price,
            'currency'   => 'USD',
        ]);

        return [$user, $product];
    }

    public function test_authenticated_user_can_create_order_from_cart(): void
    {
        [$user, $product] = $this->prepareUserWithCartItem(stock: 10, quantity: 2, price: 100.0);

        $response = $this->actingAs($user)->postJson('/api/orders');

        $response->assertCreated();
        $response->assertJsonStructure(['data' => ['orderId']]);

        $orderId = $response->json('data.orderId');

        $this->assertDatabaseHas('orders', [
            'orderId'     => $orderId,
            'customer_id' => $user->userId,
            'amount'      => 200,
            'status'      => 'new',
            'address'     => $user->address,
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id'    => $orderId,
            'product_id'  => $product->productId,
            'customer_id' => $user->userId,
            'quantity'    => 2,
            'price'       => 100,
        ]);

        $this->assertDatabaseCount('cart_items', 0);

        $this->assertDatabaseHas('product_audit', [
            'product_id' => $product->productId,
            'quantity'   => 8,
        ]);
    }

    public function test_order_creation_fails_when_cart_is_empty(): void
    {
        $user = User::factory()->create();
        Cart::create(['user_id' => $user->userId]);

        $response = $this->actingAs($user)->postJson('/api/orders');

        $response->assertStatus(500);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
    }

    public function test_order_creation_fails_when_stock_is_not_enough(): void
    {
        [$user, $product] = $this->prepareUserWithCartItem(stock: 1, quantity: 5, price: 100.0);

        $response = $this->actingAs($user)->postJson('/api/orders');

        $response->assertStatus(500);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertDatabaseHas('product_audit', [
            'product_id' => $product->productId,
            'quantity'   => 1,
        ]);

        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_guest_cannot_create_order(): void
    {
        $response = $this->postJson('/api/orders');

        $response->assertUnauthorized();
    }
}
