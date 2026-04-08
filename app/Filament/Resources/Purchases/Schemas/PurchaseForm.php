<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Models\supply;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;

class PurchaseForm
{
    protected static function updateTotals($get, $set): void
    {
        $details = $get('../../details') ?? [];

        $total = collect($details)
            ->pluck('subtotal')
            ->filter()
            ->sum();

        $set('../../preview_total', $total);
    }
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Detalles')
                        ->schema([
                            TextInput::make('preview_total')
                                ->numeric()
                                ->disabled()
                                ->live()
                                ->default(0)
                                ->prefix('$'),
                            Repeater::make('details')
                                ->relationship()
                                ->schema([
                                    Select::make('supplies_id')
                                        ->label('Insumo:')
                                        ->options(Supply::all()->pluck('name', 'id'))
                                        ->required()
                                        ->afterStateUpdated(function ($state, $get, $set) {
                                            $supply = Supply::find($state);

                                            $price = $supply?->price ?? 0;

                                            $set('price', $price);
                                            $set('stock_type', $supply?->stock_type ?? '');

                                            // recalcular subtotal
                                            $qty = $get('quantity') ?? 0;
                                            $set('subtotal', $price * $qty);

                                            // recalcular total
                                            self::updateTotals($get, $set);
                                        }),
                                    TextInput::make('quantity')
                                        ->label('Cantidad')
                                        ->numeric()
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function ($get, $set) {
                                            $price = $get('price') ?? 0;
                                            $qty = $get('quantity') ?? 0;

                                            $set('subtotal', $price * $qty);

                                            self::updateTotals($get, $set);
                                        }),
                                    TextInput::make('price')
                                        ->label('Precio unitario')
                                        ->numeric()
                                        ->disabled()
                                        ->dehydrated(),
                                    TextInput::make('stock_type')
                                        ->label('Unidad')
                                        ->disabled(),
                                    TextInput::make('subtotal')
                                        ->label('Subtotal')
                                        ->numeric()
                                        ->required()
                                        ->disabled()
                                        ->dehydrated(),
                                ])
                                ->live()
                                ->reactive()
                                ->collapsible(),
                        ]),
                    Step::make('Resumen')
                        ->schema([
                            TextInput::make('preview_total')
                                ->label('Total a pagar')
                                ->disabled()
                                ->prefix('$'),
                        ]),
                    Step::make('Confirmación')
                        ->schema([
                            TextInput::make('preview_total')
                                ->label('Total a pagar')
                                ->disabled()
                                ->prefix('$'),
                        ]),
                ])//Wizard
                    ->skippable(false)
                    ->persistStepInQueryString()
            ]);//Componentes
    }
}
