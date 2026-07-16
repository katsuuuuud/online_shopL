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
            'status'      => 'pending payment',
            'address'     => $address,
        ]);

        return $order->orderId;
    }

    public function saveOrderItems(int $orderId, array $cartItems): void
    {
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'    => $orderId,
                'product_id'  => (int)   ($item['productId'] ?? 0),
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

    public function findOwnedByUser(int $orderId, int $customerId): ?Order
    {
        return Order::where('orderId', $orderId)
            ->where('customer_id', $customerId)
            ->first();
    }

    public function updateStatus(int $orderId, string $status): void
    {
        Order::where('orderId', $orderId)->update(['status' => $status]);
    }

    public function setEpayInvoiceId(int $orderId, string $invoiceId): void
    {
        Order::where('orderId', $orderId)->update(['epay_invoice_id' => $invoiceId]);
    }

    public function findByEpayInvoiceId(string $invoiceId): ?Order
    {
        return Order::where('epay_invoice_id', $invoiceId)->first();
    }
}
