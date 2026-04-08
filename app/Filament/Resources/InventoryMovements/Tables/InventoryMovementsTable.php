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
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'purchase' => 'Compra',
                        'sale' => 'Venta',
                        'production' => 'Producción',
                        'merma' => 'Merma',
                        'adjustment' => 'Ajuste',
                        default => ucfirst($state),
                    })
                    ->color(fn($state) => match ($state) {
                        'purchase' => 'success',
                        'sale' => 'info',
                        'production' => 'warning',
                        'merma' => 'danger',
                        'adjustment' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('item_name')
                    ->label('Item')
                    ->getStateUsing(
                        fn($record) =>
                        $record->product?->name ?? $record->supply?->name
                    )
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        ($record->direction === 'out' ? '-' : '+') . $state
                    )
                    ->color(
                        fn($record) =>
                        $record->direction === 'out' ? 'danger' : 'success'
                    ),
                TextColumn::make('direction')
                    ->label('Movimiento')
                    ->formatStateUsing(
                        fn($state) =>
                        $state === 'in' ? 'Entrada' : 'Salida'
                    )
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'in' ? 'success' : 'danger'
                    ),
                TextColumn::make('reason')
                    ->label('Motivo')
                    ->limit(40)
                    ->tooltip(fn($state) => $state),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('impact')
                    ->label('Impacto $')
                    ->getStateUsing(function ($record) {

                        $quantity = $record->quantity;

                        // PRODUCTO
                        if ($record->product) {
                            $value = $record->product->price * $quantity;
                        }

                        // SUPPLY
                        elseif ($record->supply) {
                            $value = $record->supply->average_cost * $quantity;
                        } else {
                            return '$0';
                        }

                        if ($record->direction === 'out') {
                            $value *= -1;
                        }

                        return '$' . number_format($value, 2);
                    })
                    ->color(
                        fn($state) =>
                        str_contains($state, '-') ? 'danger' : 'success'
                    ),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'purchase' => 'Compra',
                        'sale' => 'Venta',
                        'production' => 'Producción',
                        'merma' => 'Merma',
                        'adjustment' => 'Ajuste',
                    ]),

                \Filament\Tables\Filters\SelectFilter::make('direction')
                    ->label('Movimiento')
                    ->options([
                        'in' => 'Entrada',
                        'out' => 'Salida',
                    ]),
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
