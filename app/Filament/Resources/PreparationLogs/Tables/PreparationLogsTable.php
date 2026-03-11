<?php

namespace App\Filament\Resources\PreparationLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PreparationLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('recipes.name')
                    ->numeric()
                    ->label("Tipo de receta")
                    ->sortable(),
                TextColumn::make('users.name')
                    ->numeric()
                    ->label("Responsable")
                    ->sortable(),
                TextColumn::make('produced_quantity')
                    ->numeric()
                    ->label("Cantidad producida")
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Día de preparación: ")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
