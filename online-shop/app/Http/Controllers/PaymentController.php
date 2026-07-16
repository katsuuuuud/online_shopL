<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\OrderRepositoryInterface;
use App\Services\PaymentService;
use App\Exceptions\DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private OrderRepositoryInterface $orderRepository,
    ) {}
    public function apiGetToken(int $orderId): JsonResponse
    {
        $order = $this->orderRepository->findOwnedByUser($orderId, Auth::id());

        if (! $order) {
            throw new DomainException('Заказ не найден.');
        }

        $token = $this->paymentService->getPaymentToken($order->orderId, (float) $order->amount);

        return response()->json([
            'data' => [
                'auth' => [
                    'access_token' => $token['access_token'],
                    'expires_in'   => $token['expires_in'],
                    'scope'        => config('epay.scope'),
                    'token_type'   => 'Bearer',
                ],
                'invoiceId'   => $token['invoice_id'],
                'amount'      => (float) $order->amount,
                'terminal'    => config('epay.terminal_id'),
                'payformJsUrl' => config('epay.payform_js_url'),
                'backLink'        => config('epay.back_link'),
                'failureBackLink' => config('epay.failure_back_link'),
                'postLink'        => config('epay.post_link'),
                'failurePostLink' => config('epay.failure_post_link'),
            ],
        ], ResponseAlias::HTTP_OK);
    }

    public function apiPostLink(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('Epay postLink получен', $payload);

        $orderId = (int) ($payload['invoiceId'] ?? 0);

        if ($orderId <= 0) {
            return response()->json(['error' => 'invoiceId отсутствует'], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $expectedHash = hash_hmac('sha256', (string) $orderId, config('epay.secret_salt'));
        $receivedHash = $payload['secret_hash'] ?? '';

        if (! hash_equals($expectedHash, $receivedHash)) {
            Log::warning('Epay postLink: неверный secret_hash', ['orderId' => $orderId]);

            return response()->json(['error' => 'Неверная подпись'], ResponseAlias::HTTP_FORBIDDEN);
        }

        $isSuccess = ($payload['code'] ?? null) === 'ok';

        $this->orderRepository->updateStatus($orderId, $isSuccess ? 'paid' : 'failed');

        return response()->json(['status' => 'received'], ResponseAlias::HTTP_OK);
    }

    public function apiPostLinkFailure(Request $request): JsonResponse
    {
        return $this->apiPostLink($request);
    }
}
