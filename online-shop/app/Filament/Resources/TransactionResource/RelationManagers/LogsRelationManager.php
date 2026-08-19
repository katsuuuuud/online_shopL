<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';

    protected static ?string $title = 'Логи (запросы шлюза)';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('event_type')
            ->columns([
                TextColumn::make('event_type')
                    ->label('Event Type'),

                TextColumn::make('direction')
                    ->label('Direction')
                    ->badge(),

                TextColumn::make('http_status')
                    ->label('HTTP'),

                IconColumn::make('signature_valid')
                    ->label('Signature Valid')
                    ->boolean(),

                TextColumn::make('ip_address')
                    ->label('IP'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form([
                        \Filament\Forms\Components\KeyValue::make('request_payload')
                            ->label('Payload')
                            ->columnSpanFull(),
                    ]),
            ])
            ->bulkActions([
                //
            ]);
    }
}
