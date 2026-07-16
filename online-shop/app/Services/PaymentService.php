<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\DomainException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    public function getPaymentToken(int $orderId, float $amount): array
    {
        $invoiceId  = $this->buildInvoiceId($orderId);
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

    private function buildInvoiceId(): string
    {
        return time() . str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
    }

    private function buildSecretHash(int $orderId): string
    {
        return hash_hmac('sha256', (string) $orderId, config('epay.secret_salt'));
    }
}
