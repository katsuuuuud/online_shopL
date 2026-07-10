<?php

namespace App\Repositories;

use App\Contracts\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function saveOrder(int $customerId, float $amount, string $address): int
    {
        $order = Order::create([
            'created_at'  => now()->toDateString(),
            'amount'      => $amount,
            'customer_id' => $customerId,
            'status'      => 'new',
            'address'     => $address,
        ]);

        return $order->orderId;
    }

    public function saveOrderItems(int $orderId, int $customerId, array $cartItems): void
    {
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'    => $orderId,
                'product_id'  => (int)   ($item['productId'] ?? 0),
                'customer_id' => $customerId,
                'quantity'    => (int)   ($item['quantity']  ?? 0),
                'price'       => (float) ($item['price']     ?? 0),
                'currency'    => strtoupper((string) ($item['currency'] ?? '')),
            ]);
        }
    }

    public function getOrdersByCustomer(int $customerId): Collection
    {
        return Order::where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->get();
    }
}
