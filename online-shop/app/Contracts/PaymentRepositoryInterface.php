<?php
declare(strict_types=1);

namespace App\Contracts;

use App\Models\Transaction;

interface PaymentRepositoryInterface
{
    public function create(int $orderId, float $amount, string $invoiceId): Transaction;

    public function findByInvoiceId(string $invoiceId): ?Transaction;

    public function markAsPaidOrFailed(Transaction $transaction, array $payload, string $status, bool $isSuccess): void;

    public function logOutgoing(Transaction $transaction, array $token, float $amount): void;

    public function logIncoming(Transaction $transaction, array $payload, int $httpStatus, bool $signatureValid, ?string $ip): void;
}
