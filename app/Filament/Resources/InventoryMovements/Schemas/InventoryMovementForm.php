<?php

namespace App\Filament\Resources\InventoryMovements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Product;
use App\Models\supply;

class InventoryMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'merma' => 'Merma',
                        'ajuste' => 'Ajuste',
                    ])
                    ->required(),

                Select::make('target')
                    ->label('Afecta a')
                    ->options([
                        'product' => 'Producto',
                        'inventory' => 'Insumo',
                    ])
                    ->required()
                    ->reactive(),

                Select::make('product_id')
                    ->label('Producto')
                    ->options(Product::pluck('name', 'id'))
                    ->visible(fn($get) => $get('target') === 'product'),

                Select::make('supply_id')
                    ->label('Insumo')
                    ->options(Supply::pluck('name', 'id'))
                    ->visible(fn($get) => $get('target') === 'inventory'),

                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->required(),

                Select::make('direction')
                    ->label('Dirección')
                    ->options([
                        'out' => 'Salida (Merma)',
                        'in' => 'Entrada (Ajuste positivo)',
                    ])
                    ->required(),

                Textarea::make('reason')
                    ->label('Motivo')
                    ->required(),

            ]);
    }
}
