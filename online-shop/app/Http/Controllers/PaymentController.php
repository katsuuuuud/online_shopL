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

        $invoiceId = (string) ($payload['invoiceId'] ?? '');

        if ($invoiceId === '') {
            return response()->json(['error' => 'invoiceId отсутствует'], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $order = $this->orderRepository->findByEpayInvoiceId($invoiceId);

        if (! $order) {
            Log::warning('Epay postLink: заказ не найден по invoiceId', ['invoiceId' => $invoiceId]);

            return response()->json(['error' => 'Заказ не найден'], ResponseAlias::HTTP_NOT_FOUND);
        }

        $expectedHash = hash_hmac('sha256', (string) $order->orderId, config('epay.secret_salt'));
        $receivedHash = $payload['secret_hash'] ?? '';

        if (! hash_equals($expectedHash, $receivedHash)) {
            Log::warning('Epay postLink: неверный secret_hash', ['orderId' => $order->orderId]);

            return response()->json(['error' => 'Неверная подпись'], ResponseAlias::HTTP_FORBIDDEN);
        }

        $isSuccess = ($payload['code'] ?? null) === 'ok';

        $this->orderRepository->updateStatus($order->orderId, $isSuccess ? 'paid' : 'failed');

        return response()->json(['status' => 'received'], ResponseAlias::HTTP_OK);
    }

    public function apiPostLinkFailure(Request $request): JsonResponse
    {
        return $this->apiPostLink($request);
    }
}
