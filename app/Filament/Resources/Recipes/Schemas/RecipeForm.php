<?php

namespace App\Filament\Resources\Recipes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RecipeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                TextInput::make('produced_quantity')
                    ->required()
                    ->numeric(),
            ]);
    }
}
