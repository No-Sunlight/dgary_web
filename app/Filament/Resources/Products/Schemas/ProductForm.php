<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Laravel\Pail\Options;


class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->default(null),
                Select::make('category_id')
                    ->label("Categoria")
                    ->options(function ():
                                    array {
                        return Category::query()->pluck('name', 'id')->all();
                    })
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->columnSpanFull()
                    ->directory('images')
                    ->disk('public')
                    ->imageEditor()
                    ->label("Imagen")
                    ->required(),
                TextInput::make('price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                TextInput::make('points')
                    ->numeric()
                    ->default(null),
                TextInput::make('stock')
                    ->numeric()
                    ->default(null),
                Select::make('type')
                    ->label('Tipo de producto')
                    ->options([
                        'unit' => 'Por pieza',
                        'weight' => 'Por peso (gramos)',
                        'volume' => 'Por volumen (ml)',
                    ])
                    ->default('unit')
                    ->required(),
            ]);
    }
}
