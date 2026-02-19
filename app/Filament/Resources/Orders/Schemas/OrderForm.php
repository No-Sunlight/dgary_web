<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        
            ->components([

Wizard::make([
    Step::make('Order data')
        ->schema([
                Select::make('customer_id')
                    ->label('Customer')
                    //->required()
                    ->searchable()
                    ->options(function ():
                    array{
                    return Customer::query()->pluck('email', 'id')->all();}),
                    Select::make('status')
                        ->options([
                        'Pending' => 'Pending',
                        'Canceled' => 'Canceled',
                        'In transit' => 'In transit',
                        'Delivered' => 'Delivered',
                        'Confirmed' => 'Confirmed',
                    ])
                    ,

        //DateTimePicker            
        DateTimePicker::make('deliver_date')
            ->seconds(false)
            //Pendiente agregar un limite para dias
    //     ->native(false)
    //     ->disabledDates(function () { 
    //     $start = Carbon::now();
    //     $end = $start->copy()->addDays(15);
    //     $period = CarbonPeriod::create($start, $end);
    //     $weekends = [];
    //     foreach ($period as $date) {
    //         if ($date->isWeekend()) {
    //             $weekends[] = $date->format('Y-m-d');
    //         }
    //     }
 
    //     return $weekends;
    // })
    , 

        Toggle::make('discount')
                    ->required(),
        ]),
 //DETAILS OF THE PRODUCT
    Step::make('Products')
        ->schema([
         TextInput::make('total')
                ->numeric()
                ->readOnly()
                ->live()
                ->default(0)
                ->prefix('$'),

            Repeater::make('details')
                    ->relationship()
    ->schema([
        Select::make('product_id')
            ->label('Product')
            ->options(Product::all()->pluck('name', 'id'))
            ->reactive()
            ->required()
        
            ->afterStateUpdated(function ($state, $get,Set $set) {
            $set('unit_price', product::find($state)?->price ?? 0);
            if(empty($get('amount'))){
                $set('subtotal', 0);
                return;
            }
        $set('subtotal', $get('amount') * $get('unit_price') );
            })

            ,
        TextInput::make('amount')
        ->numeric()
        ->required()
        ->live()
        ->dehydrated()

        ->afterStateUpdated(function ($state,$set,$get)
            {
            if (empty($state)) {
                $set('subtotal', 0);
                return;
            }
            //SI ESTO SE ACTIVA SIGNIFICA QUE ESTOY EN UN EDIT Y SOLO QUIERO CAMBIAR EL PRECIO
            if( !empty($get('product_id'))){
            $set('unit_price', product::find($get('product_id'))?->price ?? 0);
            $set('subtotal', $get('amount') * $get('unit_price'));


            }
            else{
                $set('subtotal', $get('amount') * $get('unit_price'));
            }

        }),
        TextInput::make('subtotal')
                        ->numeric()
                        ->readOnly()
                        ->live()
                        ->default(0)
                        ->prefix('$'),
            
                        ])//REPEATER
                            ->addAction(function( Get $get, Set $set){
                            $total =collect($get('details'))->pluck('subtotal')->sum();
                            $set('total',$total);}
                        
                         )



        ]),
]) //MAIN WIZARD 
           ->columnSpanFull()

            ]);
          
    }

    

}