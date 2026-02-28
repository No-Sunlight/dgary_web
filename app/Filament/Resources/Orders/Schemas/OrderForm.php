<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Customer;
use App\Models\CustomerCoupon;
use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

 //Estoy en un edit y quiero ver la información del cliente, tanto como su nombre e email   
->afterValidation(function ($set,$get) {
    
    if(!empty($get('customer_id'))){
       $customer = Customer::find($get('customer_id'));
       $set('customer_name',$customer->name);
       $set('customer_email',$customer->email);

        }

    })//After validation


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
                            $set('preview_total',$total);
                            $set('subtotal',$total);
                          //  $set('total',$total);

                            }

                        
                         )
                             ->collapsible()

        ]), //Products details
        TextInput::make("unit_price")
        ->numeric()
        ->live()
        ->hidden(),


//INFORMACION DEL CLIENTE
//Para este punto ya debería de haberse establecido tanto el cupon, descuento y subtotal. Esta función calcula el total
Step::make('Información del cliente')
->afterValidation(function ($set,$get) {
//Dios bendiga a aftervalidation
    
    $subtotal=$get('subtotal');
    $set('total', ($subtotal-$subtotal*$get('discount')/100));

    })//After validation


        ->schema([
                Select::make('customer_id')
                    ->label('Customer')
                    ->searchable()
                    ->live()
                    ->disabledOn('edit')
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
                        }else{
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

        Select::make('coupon_id')
                    ->searchable()
                    ->options(function ($get):
                    array{
                    $array=[];
                    if(!empty($get('selected_customer'))){
                    $coupons = CustomerCoupon::with('coupons')->where("customer_id","=",$get('selected_customer'))->where('status','=','1')->get();

                    foreach ($coupons as $coupon) {
                            $array[$coupon->id]=$coupon->coupons->name;}
                        return $array;
                        
                    //Customer_id no esta vacio, lo que indica que fue activado por el edit.    
                    }if(!empty($get('customer_id'))){
                            $coupons = CustomerCoupon::with('coupons')->where("customer_id","=",$get('customer_id'))->get();
                        foreach ($coupons as $coupon) {
                            $array[$coupon->id]=$coupon->coupons->name;}
                        return $array;
                }else{
                    return $array;
                    }})
                    ->afterStateUpdated(
                        function($set,$state,$get){
                            if($state>0){
                         $discount =CustomerCoupon::find($state)?->discount ?? 0;
                         $set('discount',$discount);  
                        // $set('total', ($discount/100)*$get('subtotal') );
                            }
                            //Creo que me puedo ahorrar el if else, si pongo el valor default del discount a cero
                            else{
                                $set('discount',0);
                            }
                        }),


                        

        ]),//Información


        Step::make("Confirmar pago")
        ->schema([
            //Aqui estamos confirmando que el cliente quiere hacer la compra, 
            //aunque la verdad sigo pensando que primero deberiamos de pregunta al cliente si quiere registrar sus puntos 
        TextInput::make('subtotal')
        ->readOnly(),
        TextInput::make("discount")
        ->prefix('%')
        ->default(0)
        ->readOnly(),
        TextInput::make('total')
        ->readOnly()


        ])



        ]) //MAIN WIZARD 
           ->columnSpanFull()

            ]);
          
    }

    

}