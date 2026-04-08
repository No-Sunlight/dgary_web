<?php

namespace App\Filament\Resources\InventoryCountResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Detalle de Inventario';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name')
                    ->label('Nombre')
                    ->getStateUsing(
                        fn($record) =>
                        $record->product?->name ?? $record->supply?->name
                    ),
                TextColumn::make('stock_system')
                    ->label('Sistema'),

                TextInputColumn::make('stock_real')
                    ->label('Físico')
                    ->rules(['required', 'numeric'])
                    ->disabled(fn() => $this->ownerRecord->applied)
                    ->afterStateUpdated(function ($state, $record) {

                        $difference = $state - $record->stock_system;

                        $record->update([
                            'difference' => $difference,
                        ]);
                    }),

                TextColumn::make('difference')
                    ->label('Diferencia')
                    ->color(
                        fn($state) =>
                        $state < 0 ? 'danger' : ($state > 0 ? 'success' : 'gray')
                    ),
            ])
            ->defaultSort('id');
    }
}