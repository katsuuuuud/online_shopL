<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\EpayPostLinkRequest;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
    ) {}

    public function apiGetToken(int $orderId): JsonResponse
    {
        $data = $this->paymentService->createPaymentToken($orderId, Auth::id());

        return response()->json(['data' => $data], ResponseAlias::HTTP_OK);
    }

    public function apiPostLink(EpayPostLinkRequest $request): JsonResponse
    {
        Log::info('Epay postLink получен', $request->validated());

        $result = $this->paymentService->handlePostLinkCallback($request->validated());

        return response()->json($result, ResponseAlias::HTTP_OK);
    }

    public function apiPostLinkFailure(EpayPostLinkRequest $request): JsonResponse
    {
        return $this->apiPostLink($request);
    }
}
