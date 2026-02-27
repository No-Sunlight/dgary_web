<?php

namespace App\Filament\Resources\Supplies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('stock')
                    ->required()
                    ->numeric(),
                Select::make('stock_type')
                    ->options(['liters' => 'Liters', 'kilograms' => 'Kilograms', 'units' => 'Units'])
                    ->required(),
            ]);
    }
}
