<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\PaymentRepositoryInterface;
use App\Models\Transaction;
use App\Models\TransactionLog;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function create(int $orderId, float $amount, string $invoiceId): Transaction
    {
        return Transaction::create([
            'order_id'   => $orderId,
            'invoice_id' => $invoiceId,
            'amount'     => $amount,
            'currency'   => 'KZT',
            'status'     => 'pending',
        ]);
    }

    public function findByInvoiceId(string $invoiceId): ?Transaction
    {
        return Transaction::where('invoice_id', $invoiceId)->first();
    }

    public function markAsPaidOrFailed(Transaction $transaction, array $payload, string $status, bool $isSuccess): void
    {
        $transaction->update([
            'epay_transaction_id' => $payload['id'] ?? null,
            'reference'           => $payload['reference'] ?? null,
            'approval_code'       => $payload['approvalCode'] ?? null,
            'card_mask'           => $payload['cardMask'] ?? null,
            'card_type'           => $payload['cardType'] ?? null,
            'card_id'             => $payload['cardId'] ?? null,
            'phone'               => $payload['phone'] ?? null,
            'email'               => $payload['email'] ?? null,
            'amount_bonus'        => $payload['amount_bonus'] ?? null,
            'status'              => $status,
            'paid_at'             => $isSuccess ? ($payload['dateTime'] ?? now()) : null,
        ]);
    }

    public function logOutgoing(Transaction $transaction, array $token, float $amount): void
    {
        TransactionLog::create([
            'transaction_id'  => $transaction->id,
            'event_type'      => 'request_sent',
            'direction'       => 'outgoing',
            'http_status'     => $token['http_status'],
            'request_payload' => [
                'invoiceId' => $token['invoice_id'],
                'amount'    => $amount,
                'currency'  => 'KZT',
                'terminal'  => config('epay.terminal_id'),
            ],
            'signature_valid' => null,
            'ip_address'      => null,
        ]);
    }

    public function logIncoming(Transaction $transaction, array $payload, int $httpStatus, bool $signatureValid, ?string $ip): void
    {
        TransactionLog::create([
            'transaction_id'  => $transaction->id,
            'event_type'      => 'webhook_received',
            'direction'       => 'incoming',
            'http_status'     => $httpStatus,
            'request_payload' => Arr::except($payload, ['secret_hash']),
            'signature_valid' => $signatureValid,
            'ip_address'      => $ip,
        ]);
    }
}
