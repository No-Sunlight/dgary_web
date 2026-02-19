<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description'),
                TextInput::make('discount')
                    ->required()
                    ->minValue(1)
                    ->maxValue(100)
                    ->integer()
                    ->numeric(),
                TextInput::make('price')
                //El cupon puede costar 0? Osea cuandos sea gratis
                    ->required()
                    ->integer()
                    ->prefix('$'),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
