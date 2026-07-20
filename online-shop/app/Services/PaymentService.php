<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
use App\Exceptions\DomainException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function createPaymentToken(int $orderId, int $userId): array
    {
        $order = $this->orderRepository->findOwnedByUser($orderId, $userId);

        if (! $order) {
            throw new DomainException('Заказ не найден.', 404);
        }

        $token = $this->requestPaymentToken($order->orderId, (float) $order->amount);

        $this->orderRepository->setEpayInvoiceId($order->orderId, $token['invoice_id']);

        return [
            'auth' => [
                'access_token' => $token['access_token'],
                'expires_in'   => $token['expires_in'],
                'scope'        => config('epay.scope'),
                'token_type'   => 'Bearer',
            ],
            'invoiceId'       => $token['invoice_id'],
            'amount'          => (float) $order->amount,
            'terminal'        => config('epay.terminal_id'),
            'payformJsUrl'    => config('epay.payform_js_url'),
            'backLink'        => config('epay.back_link'),
            'failureBackLink' => config('epay.failure_back_link'),
            'postLink'        => config('epay.post_link'),
            'failurePostLink' => config('epay.failure_post_link'),
        ];
    }

    public function handlePostLinkCallback(array $payload): array
    {
        $invoiceId = (string) ($payload['invoiceId'] ?? '');

        $order = $this->orderRepository->findByEpayInvoiceId($invoiceId);

        if (! $order) {
            Log::warning('Epay postLink: заказ не найден по invoiceId', ['invoiceId' => $invoiceId]);

            throw new DomainException('Заказ не найден', 404);
        }

        $receivedHash = (string) ($payload['secret_hash'] ?? '');

        if (! $this->isSignatureValid($order->orderId, $receivedHash)) {
            Log::warning('Epay postLink: неверный secret_hash', ['orderId' => $order->orderId]);

            throw new DomainException('Неверная подпись', 403);
        }

        $isSuccess = ($payload['code'] ?? null) === 'ok';

        $this->orderRepository->updateStatus($order->orderId, $isSuccess ? 'paid' : 'failed');

        return ['status' => 'received'];
    }

    private function requestPaymentToken(int $orderId, float $amount): array
    {
        $invoiceId  = $this->buildInvoiceId();
        $secretHash = $this->buildSecretHash($orderId);

        $response = Http::asForm()->post(config('epay.oauth_url'), [
            'grant_type'      => 'client_credentials',
            'scope'           => config('epay.scope'),
            'client_id'       => config('epay.client_id'),
            'client_secret'   => config('epay.client_secret'),
            'invoiceID'       => $invoiceId,
            'secret_hash'     => $secretHash,
            'amount'          => $amount,
            'currency'        => 'KZT',
            'terminal'        => config('epay.terminal_id'),
            'postLink'        => config('epay.post_link'),
            'failurePostLink' => config('epay.failure_post_link'),
        ]);

        if ($response->failed()) {
            Log::error('Epay: не удалось получить токен', [
                'orderId' => $orderId,
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);

            throw new DomainException('Не удалось начать оплату. Попробуйте позже.');
        }

        $data = $response->json();

        return [
            'access_token' => $data['access_token'],
            'expires_in'   => $data['expires_in'],
            'invoice_id'   => $invoiceId,
            'secret_hash'  => $secretHash,
        ];
    }

    private function isSignatureValid(int $orderId, string $receivedHash): bool
    {
        return hash_equals($this->buildSecretHash($orderId), $receivedHash);
    }

    private function buildInvoiceId(): string
    {
        return time() . str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
    }

    private function buildSecretHash(int $orderId): string
    {
        return hash_hmac('sha256', (string) $orderId, config('epay.secret_salt'));
    }
}
