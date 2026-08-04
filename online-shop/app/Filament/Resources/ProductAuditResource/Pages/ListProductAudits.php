<?php

namespace App\Filament\Resources\ProductAuditResource\Pages;

use App\Filament\Resources\ProductAuditResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductAudits extends ListRecords
{
    protected static string $resource = ProductAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
