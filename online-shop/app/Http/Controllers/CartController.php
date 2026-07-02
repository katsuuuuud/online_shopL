<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartItemRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function show(Request $request)
    {
        $data = $this->cartService->getItems($request);

        return view('cart', ['items' => $data['items']]);
    }

    public function apiAdd(AddCartItemRequest $request): JsonResponse
    {
        $productId = $request->validated()['productId'];
        $quantity  = $request->validated()['quantity'] ?? 1;

        $result = $this->cartService->addItem($request, $productId, $quantity);

        $response = response()->json([
            'data'  => $result['items'],
            'total' => $result['total'],
        ], 201);

        if (! $result['userId'] && $result['guestId'] && ! $request->cookie('guest_cart_id')) {
            $response->withCookie(cookie('guest_cart_id', $result['guestId'], 43200));
        }

        return $response;
    }

    public function apiRemove(Request $request, int $productId): JsonResponse
    {
        if ($productId <= 0) {
            return response()->json(['error' => 'Неверный productId'], 422);
        }

        $data = $this->cartService->removeItem($request, $productId);

        return response()->json([
            'data'  => $data['items'],
            'total' => $data['total'],
        ]);
    }

    public function apiClear(Request $request): JsonResponse
    {
        $this->cartService->clear($request);

        return response()->json(['data' => [], 'total' => 0]);
    }
}
