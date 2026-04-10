<?php

namespace App\Filament\Resources\Productions\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Product;

class ProductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Producto')
                    ->options(Product::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('quantity')
                    ->label('Cantidad a producir')
                    ->numeric()
                    ->required()
                    ->minValue(1),
            ]);
    }
}
