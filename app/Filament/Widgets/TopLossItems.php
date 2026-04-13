<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\InventoryCountItem;

class TopLossItems extends TableWidget

{
    protected static ?string $heading = 'Top items con mayor pérdida';
    protected static ?int $sort = 5;
protected int | string | array $columnSpan = 'full';


    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn() =>
                InventoryCountItem::query()
                    ->with(['product', 'supply'])
                    ->whereNotNull('stock_real')
                    ->whereColumn('stock_real', '<', 'stock_system')
            )
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Item')
                    ->getStateUsing(
                        fn($record) =>
                        $record->product?->name ?? $record->supply?->name
                    ),

                \Filament\Tables\Columns\TextColumn::make('difference')
                    ->label('Cantidad perdida')
                    ->getStateUsing(
                        fn($record) =>
                        $record->stock_system - $record->stock_real
                    )
                    ->color('danger'),

                \Filament\Tables\Columns\TextColumn::make('impact')
                    ->label('Impacto $')
                    ->getStateUsing(function ($record) {

                        $diff = $record->stock_system - $record->stock_real;

                        $cost = $record->product
                            ? $record->product->price
                            : $record->supply?->price ?? 0;

                        return '$' . number_format($diff * $cost, 2);
                    })
                    ->color('danger'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function getTableRecords(): \Illuminate\Support\Collection
    {
        return InventoryCountItem::query()
            ->with(['product', 'supply'])
            ->whereNotNull('stock_real')
            ->whereColumn('stock_real', '<', 'stock_system')
            ->get()
            ->map(function ($item) {

                $diff = $item->stock_system - $item->stock_real;

                $cost = $item->product
                    ? $item->product->price
                    : $item->supply?->price ?? 0;

                $item->impact = $diff * $cost;

                return $item;
            })
            ->sortByDesc('impact')
            ->take(5)
            ->values();
    }
}
