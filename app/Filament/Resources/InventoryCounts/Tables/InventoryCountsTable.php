<?php

namespace App\Filament\Resources\InventoryCounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoryCountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Producto'),

                TextColumn::make('inventory.name')
                    ->label('Insumo'),

                TextColumn::make('stock_system')
                    ->label('Sistema'),

                TextColumn::make('stock_real')
                    ->label('Físico'),

                TextColumn::make('difference')
                    ->label('Diferencia')
                    ->color(fn ($state) => $state < 0 ? 'danger' : 'success'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha'),
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
