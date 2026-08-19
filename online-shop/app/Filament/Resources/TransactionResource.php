<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';


    protected static ?string $navigationLabel = 'Transactions';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('order_id')
                    ->label('Order')
                    ->content(fn (?Transaction $record) => $record?->order_id),

                Placeholder::make('invoice_id')
                    ->label('Invoice ID')
                    ->content(fn (?Transaction $record) => $record?->invoice_id),

                Placeholder::make('epay_transaction_id')
                    ->label('Epay transaction ID')
                    ->content(fn (?Transaction $record) => $record?->epay_transaction_id),

                Placeholder::make('status')
                    ->label('Status')
                    ->content(fn (?Transaction $record) => $record?->status),

                Placeholder::make('amount')
                    ->label('Ammount')
                    ->content(fn (?Transaction $record) => $record ? "{$record->amount} {$record->currency}" : null),

                Placeholder::make('card_mask')
                    ->label('Card Mask')
                    ->content(fn (?Transaction $record) => $record ? trim("{$record->card_type} {$record->card_mask}") : null),

                Placeholder::make('paid_at')
                    ->label('Paid at')
                    ->content(fn (?Transaction $record) => $record?->paid_at?->format('d.m.Y H:i')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')
                    ->label('Order #')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('invoice_id')
                    ->label('Invoice ID')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'success', 'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'declined' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('amount')
                    ->label('Ammount')
                    ->formatStateUsing(fn ($state, Transaction $record) => "{$state} {$record->currency}")
                    ->sortable(),

                TextColumn::make('card_mask')
                    ->label('Card Mask')
                    ->placeholder('—'),

                TextColumn::make('paid_at')
                    ->label('Paid at')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(fn () => Transaction::query()->distinct()->pluck('status', 'status')->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }
}
