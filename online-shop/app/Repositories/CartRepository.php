<?php

namespace App\Repositories;
use App\Contracts\CartRepositoryInterface;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Redis;

class CartRepository implements CartRepositoryInterface
{
    private const GUEST_KEY_PREFIX  = 'cart:guest:';
    private const COOKIE_LIFETIME   = 2592000; // 30 days in seconds


    public function getItems(?int $userId, ?string $guestId): array
    {
        if ($userId) {
            $cartId = $this->ensureCartForUser($userId);
            return $this->getItemsByCartId($cartId);
        }

        return $this->getGuestItems($guestId ?? '');
    }

    public function addItem(
        ?int $userId,
        ?string $guestId,
        int $productId,
        string $name,
        float $price,
        string $currency,
        int $quantity = 1
    ): array {
        if ($userId) {
            $cartId = $this->ensureCartForUser($userId);

            $existing = CartItem::where('cart_id', $cartId)
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $quantity);
            } else {
                CartItem::create([
                    'cart_id'    => $cartId,
                    'product_id' => $productId,
                    'quantity'   => $quantity,
                    'price'      => $price,
                    'currency'   => $currency,
                ]);
            }

            return $this->getItemsByCartId($cartId);
        }

        $items     = $this->getGuestItems($guestId ?? '');
        $key       = (string) $productId;

        if (isset($items[$key])) {
            $items[$key]['quantity'] += $quantity;
        } else {
            $items[$key] = compact('productId', 'name', 'price', 'currency', 'quantity');
        }

        $this->saveGuestItems($guestId ?? '', $items);
        return $items;
    }

    public function removeItem(?int $userId, ?string $guestId, int $productId): array
    {
        if ($userId) {
            $cartId = $this->ensureCartForUser($userId);
            CartItem::where('cart_id', $cartId)->where('product_id', $productId)->delete();
            return $this->getItemsByCartId($cartId);
        }

        $items = $this->getGuestItems($guestId ?? '');
        unset($items[(string) $productId]);
        $this->saveGuestItems($guestId ?? '', $items);
        return $items;
    }

    public function clear(?int $userId, ?string $guestId): void
    {
        if ($userId) {
            $cartId = $this->ensureCartForUser($userId);
            CartItem::where('cart_id', $cartId)->delete();
            return;
        }

        $this->saveGuestItems($guestId ?? '', []);
    }

    public function mergeGuestCartToUser(int $userId, ?string $guestId): void
    {
        if (! $guestId || ! $this->isValidUuid($guestId)) {
            return;
        }

        $guestItems = $this->getGuestItems($guestId);

        foreach ($guestItems as $item) {
            $this->addItem(
                $userId,
                null,
                $item['productId'],
                $item['name'],
                $item['price'],
                $item['currency'],
                $item['quantity'],
            );
        }

        Redis::del(self::GUEST_KEY_PREFIX . $guestId);
    }


    private function ensureCartForUser(int $userId): int
    {
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        return $cart->cartId;
    }

    private function getItemsByCartId(int $cartId): array
    {
        $rows = CartItem::with('product')
            ->where('cart_id', $cartId)
            ->get();

        $items = [];
        foreach ($rows as $row) {
            $items[(string) $row->product_id] = [
                'productId' => $row->product_id,
                'name'      => $row->product?->name ?? '',
                'price'     => (float) $row->price,
                'currency'  => $row->currency,
                'quantity'  => $row->quantity,
            ];
        }

        return $items;
    }

    private function getGuestItems(string $guestId): array
    {
        if (! $guestId || ! $this->isValidUuid($guestId)) {
            return [];
        }

        $raw = Redis::get(self::GUEST_KEY_PREFIX . $guestId);
        if (! $raw) {
            return [];
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function saveGuestItems(string $guestId, array $items): void
    {
        if (! $guestId || ! $this->isValidUuid($guestId)) {
            return;
        }

        Redis::setex(
            self::GUEST_KEY_PREFIX . $guestId,
            self::COOKIE_LIFETIME,
            json_encode($items, JSON_UNESCAPED_UNICODE)
        );
    }

    private function isValidUuid(string $uuid): bool
    {
        return (bool) preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid
        );
    }
}
