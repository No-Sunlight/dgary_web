<?php

namespace App\Filament\Resources\InventoryMovements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoryMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('type')->label('Tipo'),

                TextColumn::make('product.name')->label('Producto'),

                TextColumn::make('inventory.name')->label('Insumo'),

                TextColumn::make('quantity')->label('Cantidad'),

                TextColumn::make('direction')->label('Dirección'),

                TextColumn::make('reason')->limit(30),

                TextColumn::make('created_at')->dateTime(),
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
