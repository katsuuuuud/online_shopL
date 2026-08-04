<?php

namespace App\Filament\Resources\ProductAuditResource\Pages;

use App\Filament\Resources\ProductAuditResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductAudit extends EditRecord
{
    protected static string $resource = ProductAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
