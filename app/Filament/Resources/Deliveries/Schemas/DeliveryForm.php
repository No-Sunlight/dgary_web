<?php

namespace App\Filament\Resources\Deliveries\Schemas;

use App\Models\Delivery;
use App\Models\Driver;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DeliveryForm
{
    public static function configure(Schema $schema): Schema
    {

    //Esta parte se va a quedar simplemente para mostrar detalles. Para la accion view
        return $schema
                      
            ->components([
                Select::make('user_id')
                    ->required()
                ->label("Repartidor")
                ->options(
                    function(Model $record)
                    {$unavailabe_drivers = Delivery::where('status','=','in_transit')->pluck('user_id')->toArray();
                     if(empty($unavailabe_drivers)){//No hay ordenes en transito asi que todos los drivers estan disponibles
                         return Driver::all()->pluck('name', 'id');
                         }
                    else{
                            $drivers = Driver::all();
                            $array =array();
                    
                           //Estoy editando y quiero cambiar el repartidor, si da empty significa que estoy asignando por primera vez
                            if(!empty($record->user_id)){
                            $array[$record->user_id]=Driver::find($record->user_id)->name;
                            }
                            foreach ($drivers as $driver){
                                    
                                if (!in_array($driver->id, $unavailabe_drivers)){
                                    $array[$driver->id]=$driver->name;}}
                                return $array;
                        }
                    })
                    ,
               
    TextInput::make('address')
                    ->disabled()
                    ->required(),
        Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'ready' => 'Ready',
            'completed' => 'Completed',
            'canceled' => 'Canceled',
            'in_transit'=> 'In Transit'
        ])
        ->disabled()

                ->required(),
                TextInput::make('total')
                    ->required()
                     ->disabled()
                    ->numeric(),
            ]);
    }
}
