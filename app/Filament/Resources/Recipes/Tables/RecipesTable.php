<?php

namespace App\Filament\Resources\Recipes\Tables;

use App\Models\PreparationLog;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Supply;
use App\Services\ProductionService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RecipesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Receta')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Producto a crear')
                    ->sortable(),
                TextColumn::make('produced_quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                Action::make('prepare_recipe')
                    ->label('Preparar')
                    ->color('success')
                    ->icon(Heroicon::RocketLaunch)
                    ->modalHeading('¿Seguro de querer hacer esta preparación?')
                    ->modalDescription('Las preparaciones deben registrarse UNA VEZ CONCLUIDAS')
                    ->schema([

                        Select::make('amount')
                            ->label('¿Cuántos lotes deseas producir?')
                            ->options([
                                '1' => '1 Lote',
                                '2' => '2 Lotes',
                                '3' => '3 Lotes',
                                '4' => '4 Lotes',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $record) {
                                $set('lote', $state * $record->produced_quantity);
                            }),

                        TextInput::make('lote')
                            ->label('Cantidad a producir')
                            ->disabled()
                            ->dehydrated(false),

                    ])
                    ->action(function ($record, array $data) {

                        try {

                            $quantity = $data['amount'] * $record->produced_quantity;

                            ProductionService::produce(
                                $record->id,
                                $quantity
                            );

                            Notification::make()
                                ->title('Producción realizada correctamente')
                                ->success()
                                ->send();

                        } catch (\Exception $e) {

                            Notification::make()
                                ->title($e->getMessage())
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
