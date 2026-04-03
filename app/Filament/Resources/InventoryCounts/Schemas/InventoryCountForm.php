<?php

namespace App\Filament\Resources\InventoryCounts\Schemas;

use App\Models\Supply;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Product;

class InventoryCountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'product' => 'Producto',
                        'inventory' => 'Insumo',
                    ])
                    ->required()
                    ->reactive(),

                Select::make('product_id')
                    ->label('Producto')
                    ->options(fn() => Product::query()->pluck('name', 'id')->toArray())
                    ->visible(fn($get) => $get('type') === 'product'),

                Select::make('supply_id')
                    ->label('Insumo')
                    ->options(fn() => Supply::query()->pluck('name', 'id')->toArray())
                    ->visible(fn($get) => $get('type') === 'inventory'),

                TextInput::make('stock_real')
                    ->label('Cantidad física')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $get, $set) {
                        $stockSystem = $get('stock_system') ?? 0;
                        $set('difference', $state - $stockSystem);
                    }),

                TextInput::make('stock_system')
                    ->label('Stock sistema')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
            ]);
    }
}
