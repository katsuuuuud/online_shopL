<?php

namespace App\Http\Controllers;

use App\Contracts\CartRepositoryInterface;
use App\Contracts\CatalogRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function __construct(
        private CartRepositoryInterface    $cartRepo,
        private CatalogRepositoryInterface $catalogRepo,
    ) {}

    // ──────────────────────── Web ────────────────────────

    public function show(Request $request)
    {
        [$userId, $guestId] = $this->resolveIds($request);
        $items = $this->cartRepo->getItems($userId, $guestId);

        return view('index', compact('items'));
    }

    // ──────────────────────── API ────────────────────────

    public function apiIndex(Request $request)
    {
        [$userId, $guestId] = $this->resolveIds($request);
        $items = $this->cartRepo->getItems($userId, $guestId);

        return response()->json([
            'data'  => array_values($items),
            'total' => $this->calcTotal($items),
        ]);
    }

    public function apiAdd(Request $request)
    {
        $productId = (int) ($request->input('productId', 0));
        $quantity  = max(1, (int) $request->input('quantity', 1));

        if ($productId <= 0) {
            return response()->json(['error' => 'productId обязателен'], 422);
        }

        $product = $this->catalogRepo->getProductById($productId);
        if (! $product) {
            return response()->json(['error' => 'Товар не найден'], 404);
        }

        $priceData = $this->catalogRepo->getActivePrice($productId);
        $price     = $priceData ? (float) $priceData->price    : 0.0;
        $currency  = $priceData ? $priceData->currency          : 'USD';

        [$userId, $guestId] = $this->resolveIds($request);

        $items = $this->cartRepo->addItem(
            $userId, $guestId,
            $productId, $product->name, $price, $currency, $quantity
        );

        $response = response()->json([
            'data'  => array_values($items),
            'total' => $this->calcTotal($items),
        ], 201);

        // Если гость без куки — устанавливаем куку с guestId
        if (! $userId && $guestId && ! $request->cookie('guest_cart_id')) {
            $response->withCookie(cookie('guest_cart_id', $guestId, 43200)); // 30 дней
        }

        return $response;
    }

    public function apiRemove(Request $request, int $productId)
    {
        if ($productId <= 0) {
            return response()->json(['error' => 'Неверный productId'], 422);
        }

        [$userId, $guestId] = $this->resolveIds($request);
        $items = $this->cartRepo->removeItem($userId, $guestId, $productId);

        return response()->json([
            'data'  => array_values($items),
            'total' => $this->calcTotal($items),
        ]);
    }

    public function apiClear(Request $request)
    {
        [$userId, $guestId] = $this->resolveIds($request);
        $this->cartRepo->clear($userId, $guestId);

        return response()->json(['data' => [], 'total' => 0]);
    }

    // ──────────────────────── Helpers ────────────────────────

    private function resolveIds(Request $request): array
    {
        $userId  = Auth::id();
        $guestId = $request->cookie('guest_cart_id');

        // Если гость без куки — генерируем UUID
        if (! $userId && ! $guestId) {
            $guestId = (string) Str::uuid();
        }

        return [$userId, $guestId];
    }

    private function calcTotal(array $items): float
    {
        return array_sum(array_map(
            fn($i) => (float) ($i['price'] ?? 0) * (int) ($i['quantity'] ?? 0),
            $items
        ));
    }
}
