<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ColorsRelationManager extends RelationManager
{
    protected static string $relationship = 'colors';

    protected static ?string $title = 'Product Color';

    protected static ?string $recordTitleAttribute = 'color';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('color_id')
                    ->label('Color')
                    ->relationship('color', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('product-colors')
                    ->visibility('public')
                    ->imageEditor()
                    ->columnSpanFull(),

                Repeater::make('variants')
                    ->label('Variants (size / SKU)')
                    ->relationship('variants')
                    ->schema([
                        Select::make('size_id')
                            ->label('Size')
                            ->relationship('size', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2)
                    ->defaultItems(0)
                    ->addActionLabel('Add Variant')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('color')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public'),

                TextColumn::make('color.name')
                    ->label('Color'),

                TextColumn::make('color.hex_code')
                    ->label('HEX')
                    ->badge(),

                TextColumn::make('variants_count')
                    ->label('Variants')
                    ->counts('variants'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
