<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->main_category
                        ? "{$record->name} ({$record->main_category})"
                        : $record->name)
                    ->searchable()
                    ->preload(),
                Select::make('discount_id')
                    ->label('Discount')
                    ->relationship('discount', 'description')
                    ->searchable()
                    ->preload(),
                Toggle::make('has_variant')
                    ->label('Has variants')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('description'),
                TextColumn::make('category.name'),
                TextColumn::make('category.main_category')
                    ->label('Раздел')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'women' => 'Women',
                        'men' => 'Men',
                        'unisex' => 'Unisex',
                        default => '—',
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'women' => 'danger',
                        'men' => 'info',
                        'unisex' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('discount.description'),
                IconColumn::make('has_variant')
                    ->label('Has variants')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('main_category')
                    ->label('Раздел')
                    ->options([
                        'women' => 'Women',
                        'men' => 'Men',
                        'unisex' => 'Unisex',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (filled($data['value'] ?? null)) {
                            $query->whereHas('category', fn (Builder $q) => $q->where('main_category', $data['value']));
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ColorsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
