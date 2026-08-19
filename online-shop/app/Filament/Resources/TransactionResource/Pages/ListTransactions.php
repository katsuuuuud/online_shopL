<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    // Транзакции формирует платёжный шлюз — кнопки "Создать" здесь нет.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
