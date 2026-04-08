<?php

namespace App\Filament\Resources\InventoryCounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use App\Models\Product;
use App\Models\Supply;
use Filament\Actions\Action;

class InventoryCountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(
                        fn($state) =>
                        $state === 'product' ? 'Productos' : 'Insumos'
                    )
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime(),
                TextColumn::make('applied')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Aplicado' : 'Pendiente')
                    ->color(fn($state) => $state ? 'success' : 'warning'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('apply')
                    ->label('Aplicar ajuste')
                    ->visible(fn($record) => !$record->applied)
                    ->action(function ($record) {
                        try {
                            $record->apply();

                            Notification::make()
                                ->title('Inventario aplicado correctamente')
                                ->success()
                                ->send();

                        } catch (\Exception $e) {

                            Notification::make()
                                ->title('Error al aplicar inventario')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
