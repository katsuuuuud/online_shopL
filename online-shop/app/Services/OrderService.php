<?php

namespace App\Services;

use App\Contracts\CartRepositoryInterface;
use App\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private CartRepositoryInterface  $cartRepository,
    ) {}

    public function createOrder(object $user, ?string $guestId): array
    {
        $userId    = $user->userId;
        $cartItems = $this->cartRepository->getItems($userId, $guestId);

        if (empty($cartItems)) {
            return ['success' => false, 'message' => 'Корзина пуста.'];
        }

        $address     = $user->address ?? '';
        $totalAmount = array_sum(
            array_map(fn($item) => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0), $cartItems)
        );

        DB::beginTransaction();

        try {
            foreach ($cartItems as $item) {
                $productId = (int) ($item['productId'] ?? 0);
                $quantity  = (int) ($item['quantity']  ?? 0);

                if ($productId <= 0 || $quantity <= 0) {
                    throw new \RuntimeException('Некорректные данные товара в корзине.');
                }

                $updated = DB::update(
                    'UPDATE product_audit SET quantity = quantity - ?
                     WHERE product_id = ? AND quantity >= ?',
                    [$quantity, $productId, $quantity]
                );

                if ($updated === 0) {
                    throw new \RuntimeException(
                        'Извините, товар "' . ($item['name'] ?? 'товар') . '" закончился'
                    );
                }
            }

            $orderId = $this->orderRepository->saveOrder($userId, (int) round($totalAmount), $address);
            $this->orderRepository->saveOrderItems($orderId, $userId, $cartItems);
            $this->cartRepository->clear($userId, $guestId);

            DB::commit();

            return ['success' => true, 'message' => 'Заказ успешно оформлен.', 'orderId' => $orderId];

        } catch (\Throwable $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Не удалось оформить заказ: ' . $e->getMessage()];
        }
    }
}
