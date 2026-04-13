<?php

namespace App\Filament\Resources\Recipes\Schemas;

use App\Models\Product;
use App\Models\Supply;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                            Select::make('product_id')
                                ->label('Producto a generar')
                                ->options(Product::query()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                            Textarea::make('description')
                                ->label("Descripción")
                                ->required(),
                            TextInput::make('produced_quantity')
                                ->label('Cantidad que produce la receta')
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
                                        ->options(Supply::all()->pluck('name', 'id'))
                                        ->required(),
                                    TextInput::make('amount')
                                        ->label('Cantidad')
                                        ->required(),

                                ])//Repeater

                        ]),//Ingredientes

                ])//Wizard
                    ->columnSpanFull()

            ]);//Componentes
    }
}
