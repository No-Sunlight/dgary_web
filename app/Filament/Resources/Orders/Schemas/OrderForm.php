<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Customer;
use App\Models\CustomerCoupon;
use App\Models\Product;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
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
            ->searchable()
            ->options(Product::all()->pluck('name', 'id'))
            ->reactive()
            ->required()
        
            ->afterStateUpdated(function ($state, $get,Set $set) {
            $set('unit_price', product::find($state)?->price ?? 0);
            if(empty($get('quantity'))){
                $set('subtotal', 0);
                return;
            }
        $set('subtotal', $get('quantity') * $get('unit_price') );

            }),
        TextInput::make('quantity')
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
            $set('subtotal', $get('quantity') * $get('unit_price'));


            }
            else{
                $set('subtotal', $get('quantity') * $get('unit_price'));
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
                            $set('total',$total);
                            $set('preview_total',$total);

                            }

                        
                         )
                             ->collapsible()

        ]), //Products details
        TextInput::make("unit_price")
        ->numeric()
        ->live()
        ->hidden(),


Step::make('Información del cliente')
        ->schema([
                Select::make('customer_id')
                    ->label('Customer')
                    ->searchable()
                    ->live()
                    ->options(function ():
                    array{
                    return Customer::query()->pluck('phone', 'id')->all();})
                    ->afterStateUpdated(function ($state, $get, $set) 
                    {
                        $customer=Customer::find($state);
                        $set('selected_customer', $state);
                        if(empty($state)){
                            $set('selected_customer', 0);
                            return;
                        }
                        else{
                         $set('customer_name', $customer->name );
                         $set('customer_email', $customer->email);
                         return;
                        }
                
                        }),


        TextInput::make("customer_name")
        ->label("Nombre")
         ->dehydrated(false)
        ->live()
        ->disabled(),
        TextInput::make("customer_email")
        ->label("email")
        ->dehydrated(false)
        ->live()
        ->disabled(),

        Select::make('discount')
                    ->searchable()
                    ->options(function ($get):
                    array{
                    $array=[];
                    if(!empty($get('selected_customer'))){
                    // 
                    $coupons = CustomerCoupon::with('coupons')->where("customer_id","=",$get('selected_customer'))->where('status','=','1')->get();
                      //Cambiar a solo disponibles  

                    foreach ($coupons as $coupon) {
                            $array[$coupon->id]=$coupon->coupons->name;}
                
                        return $array;}

                    else{
                    return $array;}}),

        ]),//Información


        Step::make("Confirmar pago")
        ->schema([
        TextEntry::make('preview_total'),
        TextEntry::make("preview_subtotal")
    
            




        ])







        ]) //MAIN WIZARD 
           ->columnSpanFull()

            ]);
          
    }

    

}