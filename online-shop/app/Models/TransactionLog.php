<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    const UPDATED_AT = null;

    protected $table = 'transaction_logs';

    protected $fillable = [
        'transaction_id',
        'event_type',
        'direction',
        'http_status',
        'request_payload',
        'signature_valid',
        'ip_address',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'signature_valid' => 'boolean',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
