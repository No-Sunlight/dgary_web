<?php

namespace App\Filament\Resources\Productions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ProductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
             ->columns([
                TextColumn::make('product.name')
                    ->label('Producto')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
