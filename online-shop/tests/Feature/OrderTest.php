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

        $response->assertStatus(422);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
    }

    public function test_order_creation_fails_when_stock_is_not_enough(): void
    {
        [$user, $product] = $this->prepareUserWithCartItem(stock: 1, quantity: 5, price: 100.0);

        $response = $this->actingAs($user)->postJson('/api/orders');

        $response->assertStatus(422);

        // транзакция должна откатиться — новых заказов и списаний быть не должно
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertDatabaseHas('product_audit', [
            'product_id' => $product->productId,
            'quantity'   => 1,
        ]);

        // корзина не должна очищаться, если заказ не создан
        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_guest_cannot_create_order(): void
    {
        $response = $this->postJson('/api/orders');

        $response->assertUnauthorized();
    }

    public function test_order_amount_sums_up_multiple_cart_items(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->userId]);

        $productA = Product::factory()->create();
        $productB = Product::factory()->create();

        ProductAudit::create(['product_id' => $productA->productId, 'quantity' => 5]);
        ProductAudit::create(['product_id' => $productB->productId, 'quantity' => 5]);

        CartItem::create([
            'cart_id'    => $cart->cartId,
            'product_id' => $productA->productId,
            'quantity'   => 2,
            'price'      => 50,
            'currency'   => 'USD',
        ]);

        CartItem::create([
            'cart_id'    => $cart->cartId,
            'product_id' => $productB->productId,
            'quantity'   => 3,
            'price'      => 30,
            'currency'   => 'USD',
        ]);

        $response = $this->actingAs($user)->postJson('/api/orders');

        $response->assertCreated();

        $orderId = $response->json('data.orderId');

        // 2*50 + 3*30 = 190
        $this->assertDatabaseHas('orders', [
            'orderId' => $orderId,
            'amount'  => 190,
        ]);

        $this->assertDatabaseCount('order_items', 2);
    }

    public function test_active_percentage_discount_is_applied_to_order_price(): void
    {
        $user     = User::factory()->create();
        $discount = \App\Models\Discount::factory()->create([
            'type'           => 'percentage',
            'discount_value' => 10,
            'is_active'      => true,
        ]);
        $product = Product::factory()->create(['discount_id' => $discount->discountId]);

        ProductAudit::create(['product_id' => $product->productId, 'quantity' => 5]);

        $cart = Cart::create(['user_id' => $user->userId]);
        CartItem::create([
            'cart_id'    => $cart->cartId,
            'product_id' => $product->productId,
            'quantity'   => 1,
            'price'      => 100,
            'currency'   => 'USD',
        ]);

        $response = $this->actingAs($user)->postJson('/api/orders');

        $response->assertCreated();

        $orderId = $response->json('data.orderId');

        $this->assertDatabaseHas('orders', [
            'orderId' => $orderId,
            'amount'  => 90,
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $orderId,
            'price'    => 90,
        ]);
    }

    public function test_inactive_discount_is_not_applied_to_order_price(): void
    {
        $user     = User::factory()->create();
        $discount = \App\Models\Discount::factory()->inactive()->create([
            'type'           => 'percentage',
            'discount_value' => 10,
        ]);
        $product = Product::factory()->create(['discount_id' => $discount->discountId]);

        ProductAudit::create(['product_id' => $product->productId, 'quantity' => 5]);

        $cart = Cart::create(['user_id' => $user->userId]);
        CartItem::create([
            'cart_id'    => $cart->cartId,
            'product_id' => $product->productId,
            'quantity'   => 1,
            'price'      => 100,
            'currency'   => 'USD',
        ]);

        $response = $this->actingAs($user)->postJson('/api/orders');

        $response->assertCreated();

        $orderId = $response->json('data.orderId');

        // скидка неактивна — цена не должна измениться
        $this->assertDatabaseHas('orders', [
            'orderId' => $orderId,
            'amount'  => 100,
        ]);
    }

    public function test_order_uses_user_address(): void
    {
        [$user, ] = $this->prepareUserWithCartItem(stock: 5, quantity: 1);
        $user->forceFill(['address' => 'Almaty, Abay street 1'])->save();

        $response = $this->actingAs($user)->postJson('/api/orders');

        $response->assertCreated();

        $orderId = $response->json('data.orderId');

        $this->assertDatabaseHas('orders', [
            'orderId' => $orderId,
            'address' => 'Almaty, Abay street 1',
        ]);
    }
}
