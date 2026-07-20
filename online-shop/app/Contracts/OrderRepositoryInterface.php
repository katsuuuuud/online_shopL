<?php
declare(strict_types=1);
namespace App\Contracts;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function saveOrder(int $customerId, float $amount, string $address): int;
    public function saveOrderItems(int $orderId, array $cartItems): void;
    public function getOrdersByCustomer(int $customerId): Collection;
    public function findOwnedByUser(int $orderId, int $customerId): ?Order;
    public function updateStatus(int $orderId, string $status): void;
    public function setEpayInvoiceId(int $orderId, string $invoiceId): void;
    public function findByEpayInvoiceId(string $invoiceId): ?Order;
}
