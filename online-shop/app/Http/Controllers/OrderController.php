<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function apiCreate(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Требуется авторизация'], 401);
        }

        $guestId = $request->cookie('guest_cart_id');
        $result  = $this->orderService->createOrder(Auth::user(), $guestId);

        if (! $result['success']) {
            return response()->json(['error' => $result['message']], 422);
        }

        return response()->json(['data' => ['orderId' => $result['orderId']]], 201);
    }
}
