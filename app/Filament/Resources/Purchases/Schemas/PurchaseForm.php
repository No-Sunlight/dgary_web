<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Models\PurchaseSupply;
use App\Models\supply;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
              Wizard::make([
    Step::make('Compra: ')
        ->schema([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('total')
                    ->required()
                    ->numeric(),
        ]),
    Step::make('details')
    ->schema([
     Repeater::make('details')
                    ->relationship()
        ->schema([
             Select::make('supplies_id')
            ->label('Insumo')
            ->options(supply::all()->pluck('name', 'id'))
            ->required(),
             TextInput::make('quantity')
            ->label('Cantidad')
            ->required(),
            TextInput::make('Subtotal')
            ->label('')            ,
            
        ])//Repeater

        ]),//Ingredientes

])//Wizard
            ]);//Componentes
        
    }
}
