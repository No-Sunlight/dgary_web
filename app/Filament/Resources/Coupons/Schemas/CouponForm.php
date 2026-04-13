<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
                    ->prefix('%')
                    ->integer()
                    ->numeric(),
                TextInput::make('points_price')
                //El cupon puede costar 0? Osea cuandos sea gratis
                    ->required()
                    ->integer()
                    ->prefix('$'),
                TextInput::make('code')  
                ->required()
                ->unique(ignoreRecord: true)
                ->default(fn () => 'CPN-' . strtoupper(Str::random(6)))
                ->readOnly('edit'),
            Toggle::make('status')
                ->required(),
            ]);
    }
}
