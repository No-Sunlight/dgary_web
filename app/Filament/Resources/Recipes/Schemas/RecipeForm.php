<?php

namespace App\Filament\Resources\Recipes\Schemas;

use App\Models\Product;
use App\Models\supply;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;

class RecipeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
              Wizard::make([
    Step::make('Tipo de receta: ')
        ->schema([
                TextInput::make('name')
                    ->label("Nombre")
                    ->required(),
                TextInput::make('description')
                    ->label("Descripción")
                    ->required(),
                Select::make('product_id')
                    ->label("Producto")
                 ->options(Product::all()->pluck('name', 'id'))

                    ->required(),
                TextInput::make('produced_quantity')
                    ->required()
                    ->numeric(),
        ]),
    Step::make('Ingredientes')
    ->schema([
     Repeater::make('details')
                    ->relationship()
        ->schema([
             Select::make('supply_id')
            ->label('Insumo')
            ->options(supply::all()->pluck('name', 'id'))
            ->required(),
             TextInput::make('amount')
            ->label('Cantidad')
            ->required(),
            
        ])//Repeater

        ]),//Ingredientes

])//Wizard
            ]);//Componentes
    }
}
