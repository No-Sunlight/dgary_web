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
        ]),//Compra Step
    Step::make('details')
    ->schema([
    TextInput::make('preview_total')
                ->numeric()
                ->disabled()
                ->live()
                ->default(0)
                ->prefix('$'),
     Repeater::make('details')
            ->relationship()
            ->schema([
             Select::make('supplies_id')
            ->label('Insumo:')
            ->options(supply::all()->pluck('name', 'id'))
          // ->relationship('supplies', 'name')

            ->required(),
             TextInput::make('quantity')
            ->label('Cantidad')
            ->required(),
            TextInput::make('Subtotal')
            ->label('Subtotal')
            ->required(),
        ])//Repeater
         ->addAction(function(  $get,  $set){
                            $total =collect($get('details'))->pluck('subtotal')->sum();
                            $set('preview_total',$total); //Deberiamos de tener un subtotal y total
                            //No estoy muy seguro, una parte de mi dice que no es necesario puesto no creo
                            $set('subtotal',$total);})
                             ->collapsible()

        ]),//Ingredientes comprados


    Step::make("Confirmación")

->afterValidation( function($get,$set){
            $subtotal=$get('subtotal');

                }
            )

    ->schema([
            
        ])


])//Wizard
            ]);//Componentes
        
    }
}
